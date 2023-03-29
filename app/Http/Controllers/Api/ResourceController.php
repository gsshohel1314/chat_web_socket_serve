<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResourceRequest;
use App\Http\Resources\ResourceCccCollection;
use App\Http\Resources\ResourceCccResource;
use App\Interfaces\ResourceInterface;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    protected $resource;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource=$resource;
    }

    public function index()
    {
        if (request()->per_page) {
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = Resource::query()
                ->where($fieldName, 'LIKE', "%$keyword%")
                ->orderBy('id', 'asc')
                ->paginate($perPage);

            return new ResourceCccCollection($query);
        } else {
            $resources = Resource::query()->where('status' ,'Active')->paginate(5);

            return new ResourceCccCollection($resources);
        }
    }

    public function deletedListIndex()
    {
        $resource = $this->resource->onlyTrashed();
        return response()->json($resource);
    }

    public function store(ResourceRequest $request)
    {
        $resource = $this->resource->create($request);

        return new ResourceCccResource($resource);
    }

    public function show(Resource $resource)
    {
        $resource = $this->resource->findOrFail($resource->id);
        return response()->json($resource);
    }

    public function edit(Resource $resource)
    {
        $resource = $this->resource->findOrFail($resource->id);
        return response()->json($resource);
    }

    public function update(ResourceRequest $request, Resource $resource)
    {
        $resource = $this->resource->update($resource->id,$request);
        $request['update'] = 'update';
        return new ResourceCccResource($resource);
    }

    public function destroy(Resource $resource)
    {
        $this->resource->delete($resource->id);
        return response()->json([
            'message' => trans('resource.deleted'),
        ], 200);
    }

    public function restore($id)
    {
        $this->resource->restore($id);
        return response()->json([
            'message' => trans('resource.restored'),
        ], 200);
    }

    public function forceDelete($id)
    {
        $this->resource->forceDelete($id);
        return response()->json([
            'message' => trans('resource.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->resource->status($request->id);
        return response()->json([
            'message' => trans('resource.status_updated'),
        ], 200);
    }
}
