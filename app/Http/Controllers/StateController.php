<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexStateGetRequest;
use App\Http\Requests\StoreStatePostRequest;
use App\Http\Requests\UpdateStatePutPatchRequest;
use App\Http\Resources\State as StateResource;
use App\Repositories\State\Repository;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexStateGetRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(IndexStateGetRequest $request)
    {
        $repository = new Repository($request);

        return StateResource::collection($repository->getIndexResources());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStatePostRequest $request
     * @return StateResource|\Illuminate\Http\JsonResponse
     */
    public function store(StoreStatePostRequest $request)
    {
        return (new Repository($request))->createResource($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return StateResource
     */
    public function show($id)
    {
        return (new Repository(request()))->showResourceDetailed($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStatePutPatchRequest $request
     * @param int $id
     * @return StateResource|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateStatePutPatchRequest $request, $id)
    {
        return (new Repository($request))->updateResource($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return (new Repository(request()))->destroyResources($id);
    }
}
