<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Interfaces\RoleInterface;
use App\Models\Menu;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserMenuAction;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $role;

    public function __construct(RoleInterface $role)
    {
        $this->role = $role;
    }
    public function index()
    {
        return $this->role->get();
    }

    public function deletedListIndex()
    {
        $deleted_list = $this->role->onlyTrashed();
        return response()->json($deleted_list);
    }

    public function store(Request $request)
    {
        $role = $this->role->create($request);
        return new RoleResource($role);
    }


    public function show(Role $role)
    {
        $role = $this->role->findOrFail($role->id);
        return response()->json($role);
    }

    public function edit($id)
    {
        $role = $this->role->findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role =  $this->role->update($id,$request);
        $request['update'] = "update";
        return new RoleResource($role);
    }

    public function destroy(Role $role)
    {
        return $this->role->delete($role->id);
    }

    public function restore($id)
    {
        return $this->role->restore($id);
    }

    public function forceDelete($id)
    {
        return $this->role->forceDelete($id);
    }

    public function permission($id)
    {
        $data['role'] = Role::findOrFail($id);
        $data['menus'] = Menu::where('parent_id',null)->where('status',1)->get();
        $data['user_menu_action'] = UserMenuAction::where('status',1)->get();
        $data['menu_permission'] = RolePermission::where('permission_type','menu')->where('role_id',$id)->pluck('permission_id')->toArray();
        $data['menu_action_permission'] = RolePermission::where('permission_type','menu_action')->where('role_id',$id)->pluck('permission_id')->toArray();

        if(count(request()->all()) > 0 && request()->isMethod('POST')){
            return $this->role->permission($id);
        }else{
           return  response()->json($data);
        }
    }

    public function status(Request $request)
    {
        return $this->role->status($request->id);
    }
}
