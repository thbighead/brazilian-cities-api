<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexCityGetRequest;
use App\Http\Requests\StoreCityPostRequest;
use App\Http\Requests\UpdateCityPutPatchRequest;
use App\Http\Resources\City as CityResource;
use App\Repositories\City\Repository;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexCityGetRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(IndexCityGetRequest $request)
    {
        $repository = new Repository($request);

        return CityResource::collection($repository->getIndexResources());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCityPostRequest $request
     * @return CityResource|\Illuminate\Http\JsonResponse
     */
    public function store(StoreCityPostRequest $request)
    {
        return (new Repository($request))->createResource($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return CityResource
     */
    public function show($id)
    {
        return (new Repository(request()))->showResourceDetailed($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCityPutPatchRequest $request
     * @param int $id
     * @return CityResource|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateCityPutPatchRequest $request, $id)
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
