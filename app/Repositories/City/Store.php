<?php

namespace App\Repositories\City;

use App\City;
use App\Http\Resources\City as CityResource;
use App\State;
use Illuminate\Http\Request;

trait Store
{
    public function createResource(Request $request)
    {
        $newCity = $request->only([
            'state_acronym',
            'name'
        ]);

        $relatedState = State::whereAcronym($newCity['state_acronym'] = strtoupper($newCity['state_acronym']))->first();
        if (is_null($relatedState)) return response()->json([
            'message' => "State of acronym {$newCity['state_acronym']} not found"
        ], 422);

        $newCity = City::create([
            'state_id' => $relatedState->id,
            'name' => $newCity['name'],
        ]);

        $newCity->state; // loading relationship

        return new CityResource($newCity);
    }
}
