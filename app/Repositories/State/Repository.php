<?php

namespace App\Repositories\State;

use Illuminate\Http\Request;

class Repository
{
    use Destroy, Index, Show;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
