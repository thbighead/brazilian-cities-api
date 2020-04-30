<?php

namespace App\Repositories\City;

use App\City;
use App\Http\Resources\City as CityResource;

trait Show
{
    public function showResourceDetailed($id)
    {
        $resource = City::with('state')->find($id);

        if (is_null($resource)) return response()->json([
            'message' => 'City not found'
        ], 404);

        return new CityResource($resource);
    }
}
