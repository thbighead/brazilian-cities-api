<?php

namespace App\Repositories\City;

use App\City;
use App\Http\Requests\UpdateCityPutPatchRequest;
use App\Http\Resources\City as CityResource;
use App\State;
use Illuminate\Database\QueryException;

trait Update
{
    public function updateResource(UpdateCityPutPatchRequest $request, $id)
    {
        if ($this->checkEmptyRequestBody($request, $updatedCity)) return response()->json(null, 204);

        if (is_null($city = City::find($id))) return $this->returnUnprocessableEntityResponse('City not found');

        if (key_exists('state_acronym', $updatedCity)) {
            if (is_null($relatedState = $this->getStateByAcronym($updatedCity)))
                return $this->returnUnprocessableEntityResponse(
                    "State of acronym {$updatedCity['state_acronym']} not found"
                );

            $city->state_id = $relatedState->id;
        }

        if (key_exists('name', $updatedCity)) $city->name = $updatedCity['name'];

        if(!$this->saveCityOrFail($city)) return $this->returnUnprocessableEntityResponse('This city already exists');

        $city->state; // loading relationship

        return new CityResource($city);
    }

    private function checkEmptyRequestBody(UpdateCityPutPatchRequest $request, array &$updatedCity)
    {
        return empty($updatedCity = $request->only([
            'state_acronym',
            'name'
        ]));
    }

    private function getStateByAcronym(array &$updatedCity)
    {
        return State::whereAcronym(
            $updatedCity['state_acronym'] = strtoupper($updatedCity['state_acronym'])
        )->first();
    }

    private function returnUnprocessableEntityResponse($message)
    {
        return response()->json([
            'message' => $message
        ], 422);
    }

    private function saveCityOrFail(City $city)
    {
        try {
            return $city->save();
        } catch (QueryException $e) {
            return false;
        }
    }
}
