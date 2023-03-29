<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CccUpdatesRequest;
use App\Http\Resources\CccUpdatesCollection;
use App\Http\Resources\CccUpdatesResource;
use App\Interfaces\CccUpdatesInterface;
use App\Models\CccUpdates;
use Illuminate\Http\Request;

class CccUpdatesController extends Controller
{
    protected $ccc_updates;

    public function __construct(CccUpdatesInterface $ccc_updates)
    {
        $this->ccc_updates = $ccc_updates;
    }

    public function index()
    {
        if (request()->per_page){
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = CccUpdates::query()
                ->with('ccc_updates')
                ->where($fieldName, 'LIKE', "%$keyword%")
                ->orderBy('id', 'asc')
                ->paginate($perPage);

            return new CccUpdatesCollection($query);
        } else{
            if (request()->type){
                $ccc_updates = CccUpdates::query()->with('ccc_updates')->where('published',1)->where('types',request()->type)->paginate(6);
            }else{
                $ccc_updates = CccUpdates::query()->with('ccc_updates')->where('published',1)->paginate(6);
            }

            return new CccUpdatesCollection($ccc_updates);
        }

    }

    public function deletedListIndex()
    {
        $ccc_updates = $this->ccc_updates->onlyTrashed();
        return response()->json($ccc_updates);
    }

    public function store(CccUpdatesRequest $request)
    {
        $data = $request;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'ccc_updates',
                    'images' => $data->image,
                    'directory' => 'cccUpdates',
                    'input_field' => 'image',
                    'width' => '416',
                    'height' => '277',
                ],
            ],
        ];
        $cccUpdates = $this->ccc_updates->create($data, $parameters);
        $cccCategory = $cccUpdates->categories()->attach($request->categories);

        return new CccUpdatesResource($cccUpdates, $cccCategory);
    }

    public function show(CccUpdates $ccc_updates)
    {
        $ccc_updates = $this->ccc_updates->findOrFail($ccc_updates->id);
        return response()->json($ccc_updates);
    }

    public function edit(CccUpdates $ccc_updates)
    {
        $ccc_updates = $this->ccc_updates->findOrFail($ccc_updates->id);
        return response()->json($ccc_updates);
    }

    public function update(CccUpdatesRequest $request)
    {
        $data = $request;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'ccc_updates',
                    'images' => $data->image,
                    'directory' => 'cccUpdates',
                    'input_field' => 'image',
                    'width' => '416',
                    'height' => '277',
                ],
            ],
        ];
        $ccc_updates = $this->ccc_updates->update($request->id,$data, $parameters);
        $cccCategory = $ccc_updates->categories()->attach($request->categories);
        $request['update'] = 'update';

        return new CccUpdatesResource($ccc_updates, $cccCategory);
    }

    public function destroy(CccUpdates $ccc_update)
    {
        $this->ccc_updates->delete($ccc_update->id);
        return response()->json([
            'message' => trans('ccc_updates.deleted'),
        ], 200);
    }

    public function restore($id)
    {
        $this->ccc_updates->restore($id);
        return response()->json([
            'message' => trans('ccc_updates.restored'),
        ], 200);
    }

    public function forceDelete($id)
    {
        $this->ccc_updates->forceDelete($id);
        return response()->json([
            'message' => trans('ccc_updates.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->ccc_updates->status($request->id);
        return response()->json([
            'message' => trans('ccc_updates.status_updated'),
        ], 200);
    }
}
