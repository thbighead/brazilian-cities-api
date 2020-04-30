<?php

namespace App\Repositories\State;

use App\State;
use App\Http\Resources\State as StateResource;
use Illuminate\Http\Request;

trait Store
{
    public function createResource(Request $request)
    {
        $newState = State::create($request->only([
            'acronym',
            'name'
        ]));

        $newState->cities; // loading relationship

        return new StateResource($newState);
    }
}
