<?php

namespace App\Repositories\State;

use App\Http\Requests\UpdateStatePutPatchRequest;
use App\Http\Resources\State as StateResource;
use App\State;

trait Update
{
    public function updateResource(UpdateStatePutPatchRequest $request, $id)
    {
        $updatedState = $request->only([
            'acronym',
            'name'
        ]);

        if (empty($updatedState)) return response()->json(null, 204);

        $state = State::find($id);

        if (is_null($state)) return response()->json([
            'message' => 'State not found',
        ], 404);

        if (key_exists('acronym', $updatedState)) $state->acronym = $updatedState['acronym'];
        if (key_exists('name', $updatedState)) $state->name = $updatedState['name'];

        $state->save();

        $state->cities; // loading relationship

        return new StateResource($state);
    }
}
