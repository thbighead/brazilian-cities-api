<?php

namespace App\Repositories\City;

use App\City;
use App\Http\Requests\UpdateCityPutPatchRequest;
use App\Http\Resources\City as CityResource;
use App\State;

trait Update
{
    public function updateResource(UpdateCityPutPatchRequest $request, $id)
    {
        $updatedCity = $request->only([
            'state_acronym',
            'name'
        ]);

        if (empty($updatedCity)) return response()->json(null, 204);

        $city = City::find($id);

        if (is_null($city)) return response()->json([
            'message' => 'City not found',
        ], 422);

        if (key_exists('state_acronym', $updatedCity)) {
            $relatedState = State::whereAcronym($updatedCity['state_acronym'] = strtoupper($updatedCity['state_acronym']))
                ->first();

            if (is_null($relatedState)) return response()->json([
                'message' => "State of acronym {$updatedCity['state_acronym']} not found"
            ], 422);

            $city->state_id = $relatedState->id;
        }

        if (key_exists('name', $updatedCity)) $city->name = $updatedCity['name'];

        $city->save();

        $city->state; // loading relationship

        return new CityResource($city);
    }
}
