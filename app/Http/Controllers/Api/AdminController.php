<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use function Psr\Log\error;

class AdminController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $admin = User::query()->where('is_admin','Yes')->get();

        return response()->json($admin);
    }

    public function deletedListIndex()
    {
        $admin = User::query()->where('is_admin','Yes')->onlyTrashed()->get();

        return response()->json($admin);
    }

    public function create()
    {
        //
    }

    public function store(UserRequest $request)
    {
        if ($request['is_admin'] == 'yes'){
            $admin = $this->user->create($request);

            return response()->json([
                'data' => $admin,
                'message' => trans('admin.created'),
            ], 200);
        } else{
            return response()->json([
                "error" => trans('admin.wrong_values')
            ],422);
        }
    }

    public function show($id)
    {
        $userId = Crypt::decrypt($id); //decrypt the id
        $admin = $this->user->findOrFail($userId);

        return response()->json($admin);
    }

    public function edit($id)
    {
        $userId = Crypt::decrypt($id); //decrypt the id
        $admin = $this->user->findOrFail($userId);

        return response()->json($admin);
    }

    public function update(Request $request, $id)
    {
        $userId = Crypt::decrypt($id); //decrypt the id
        $admin = $this->user->update($userId, $request);

        return response()->json([
            'data' => $admin,
            'message' => trans('admin.updated'),
        ], 200);
    }

    public function destroy($id)
    {
        $userId = Crypt::decrypt($id); //decrypt the id
        $this->user->delete($userId);

        return response()->json([
            'message' => trans('admin.deleted'),
        ], 200);
    }

    public function restore($id)
    {
        $userId = Crypt::decrypt($id); //decrypt the id
        $this->user->restore($userId);

        return response()->json([
            'message' => trans('admin.restored'),
        ], 200);
    }

    public function forceDelete($id) //decrypt the id
    {
        $userId = Crypt::decrypt($id);
        $this->user->forceDelete($userId);

        return response()->json([
            'message' => trans('admin.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->user->status($request->id);

        return response()->json([
            'message' => trans('admin.status_updated'),
        ], 200);
    }
}
