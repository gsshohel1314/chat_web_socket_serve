<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WorkshopRequest;
use App\Http\Resources\WorkshopCollection;
use App\Http\Resources\WorkshopResource;
use App\Interfaces\WorkshopInterface;
use App\Models\Workshop;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    protected $workshop;

    public function __construct(WorkshopInterface $workshop)
    {
        $this->workshop = $workshop;
    }

    public function index()
    {
        if (request()->per_page){
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = Workshop::query()
                ->where($fieldName, 'LIKE', "%$keyword%")
                ->orderBy('id', 'asc')
                ->paginate($perPage);

            return new WorkshopCollection($query);
        } else{
            $workshop = Workshop::query()->where('status','Active')->paginate(4);

            return new WorkshopCollection($workshop);
        }



    }

    public function deletedListIndex()
    {
        $workshop = $this->workshop->onlyTrashed();
        return response()->json($workshop);
    }

    public function store(WorkshopRequest $request)
    {
        $workshop = $this->workshop->create($request);
        return new WorkshopResource($workshop);
    }

    public function show(Workshop $workshop)
    {
        $workshop = $this->workshop->findOrFail($workshop->id);
        return response()->json($workshop);
    }

    public function edit($id)
    {
        $workshop = $this->workshop->findOrFail($id);
        return response()->json($workshop);
    }

    public function update(WorkshopRequest $request, Workshop $workshop)
    {
        $workshop = $this->workshop->update($workshop->id,$request);
        $request['update'] = 'update';
        return new WorkshopResource($workshop);
    }

    public function destroy(Workshop $workshop)
    {
        $this->workshop->delete($workshop->id);
        return response()->json([
            'message' => trans('workshop.deleted'),
        ], 200);
    }

    public function restore($id)
    {
        $this->workshop->restore($id);
        return response()->json([
            'message' => trans('workshop.restored'),
        ], 200);
    }

    public function forceDelete($id)
    {
        $this->workshop->forceDelete($id);
        return response()->json([
            'message' => trans('workshop.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->workshop->status($request->id);
        return response()->json([
            'message' => trans('workshop.status_updated'),
        ], 200);
    }
}
