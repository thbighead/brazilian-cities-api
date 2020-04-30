<?php

namespace App\Repositories\City;

use App\City;
use App\Http\Resources\City as CityResource;
use App\State;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

trait Store
{
    public function createResource(Request $request)
    {
        $newCity = $request->only([
            'state_acronym',
            'name'
        ]);

        $relatedState = State::whereAcronym($newCity['state_acronym'] = strtoupper($newCity['state_acronym']))
            ->first();
        if (is_null($relatedState)) return response()->json([
            'message' => "State of acronym {$newCity['state_acronym']} not found"
        ], 422);

        if (($newCity = $this->createCityOrFail([
                'state_id' => $relatedState->id, 'name' => $newCity['name']
            ])) === false) return response()->json(['message' => 'This city already exists'], 422);

        $newCity->state; // loading relationship

        return new CityResource($newCity);
    }

    private function createCityOrFail(array $fields)
    {
        try {
            return City::create($fields);
        } catch (QueryException $e) {
            return false;
        }
    }
}
