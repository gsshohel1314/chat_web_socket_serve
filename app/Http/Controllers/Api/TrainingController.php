<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TrainingRequest;
use App\Http\Resources\TrainingCollection;
use App\Http\Resources\TrainingResource;
use App\Interfaces\TrainingInterface;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    protected $training;

    public function __construct(TrainingInterface $training)
    {
        $this->training = $training;
    }

    public function index()
    {
        if(request()->per_page){
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;
    
            $query = Training::query()
                ->where($fieldName, 'LIKE', "%$keyword%")
                ->orderBy('id', 'asc')
                ->paginate($perPage);
        } else{
            $query = $this->training->get();
        }
       

        return new TrainingCollection($query);

        /*$training = $this->training->get();
        return response()->json($training);*/
    }

    public function deletedListIndex()
    {
        $training = $this->training->onlyTrashed();
        return response()->json($training);
    }

    public function store(TrainingRequest $request)
    {
        $training = $this->training->create($request);
        return new TrainingResource($training);
    }

    public function show(Training $training)
    {
        $training = $this->training->findOrFail($training->id);
        return response()->json($training);
    }

    public function edit($id)
    {
        $training = $this->training->findOrFail($id);
        return response()->json($training);
    }

    public function update(TrainingRequest $request, Training $training)
    {
        $training = $this->training->update($training->id,$request);
        $request['update'] = 'update';
        return new TrainingResource($training);
    }

    public function destroy(Training $training)
    {
        $this->training->delete($training->id);
        return response()->json([
            'message' => trans('training.deleted'),
        ], 200);
    }

    public function restore($id)
    {
        $this->training->restore($id);
        return response()->json([
            'message' => trans('training.restored'),
        ], 200);
    }

    public function forceDelete($id)
    {
        $this->training->forceDelete($id);
        return response()->json([
            'message' => trans('training.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->training->status($request->id);
        return response()->json([
            'message' => trans('training.status_updated'),
        ], 200);
    }
}
