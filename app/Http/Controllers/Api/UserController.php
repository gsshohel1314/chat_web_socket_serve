<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $perPage = request()->per_page;
        $fieldName = request()->field_name;
        $keyword = request()->keyword;

        $query = User::query()
            ->where($fieldName, 'LIKE', "%$keyword%")
            ->orderBy('id', 'asc')
            ->paginate($perPage);

        return new UserCollection($query);

        /* $user = $this->user->get();
         return response()->json($user);*/
    }

    public function deletedListIndex()
    {
        $user = $this->user->onlyTrashed();
        return response()->json($user);
    }

    public function store(UserRequest $request)
    {
        $user = $this->user->create($request);
        return new UserResource($user);
    }

    public function show(User $user)
    {
        $user = $this->user->findOrFail($user->id);
        return response()->json($user);
    }

    public function edit($id)
    {
        $user = $this->user->findOrFail($id);
        return response()->json($user);
    }

    public function update(UserRequest $request, User $user)
    {
        $user = $this->user->update($user->id,$request);
        $request['update'] = 'update';
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->user->delete($user->id);
        return response()->json([
            'message' => trans('user.deleted'),
        ], 200);
    }

    public function restore($id)
    {
        $this->user->restore($id);
        return response()->json([
            'message' => trans('user.restored'),
        ], 200);
    }

    public function forceDelete($id)
    {
        $this->user->forceDelete($id);
        return response()->json([
            'message' => trans('user.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->user->status($request->id);
        return response()->json([
            'message' => trans('user.status_updated'),
        ], 200);
    }

    public function systemUsers()
    {
        $users = User::query()->select('name','email','id')->get();

        return response()->json($users);
    }
}
