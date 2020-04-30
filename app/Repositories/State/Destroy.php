<?php

namespace App\Repositories\State;

use App\State;

trait Destroy
{
    public function destroyResources($id)
    {
        $destroyed = State::destroy([$id]);
        return response()->json([
            'message' => $destroyed > 0 ? "$destroyed states removed." : 'Not found',
        ], $destroyed > 0 ? 200 : 404);
    }
}
