<?php

namespace App\Repositories\City;

use App\Services\GenericRepository;
use Illuminate\Http\Request;

class Repository
{
    use Destroy, Index, Show, Store, Update;

    private $request;
    /** @var GenericRepository $repository */
    private $repository;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
