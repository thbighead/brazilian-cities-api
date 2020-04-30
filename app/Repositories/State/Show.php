<?php

namespace App\Repositories\State;

use App\State;
use App\Http\Resources\State as StateResource;

trait Show
{
    public function showResourceDetailed($id)
    {
        $resource = State::with('cities')->find($id);

        if (is_null($resource)) return response()->json([
            'message' => 'State not found'
        ], 404);

        return new StateResource($resource);
    }
}
