<?php

namespace App\Repositories\City;

use App\City;

trait Destroy
{
    public function destroyResources($id)
    {
        $destroyed = City::destroy([$id]);
        return response()->json([
            'message' => $destroyed > 0 ? "$destroyed cities removed." : 'Not found',
        ], $destroyed > 0 ? 200 : 404);
    }
}
