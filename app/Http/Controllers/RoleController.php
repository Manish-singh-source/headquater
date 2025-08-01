<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function index()
    {
        $roles = Role::get();
        return view('roles.index', ['roles' => $roles]);
    }
    
    public function create()
    {
        return view('roles.create');
    }
     public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Role::destroy($ids);
        return redirect()->back()->with('success', 'Selected Role deleted successfully.');
    }
     public function toggleStatus(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $role->status = $request->status;
        $role->save();

        return response()->json(['success' => true]);
    }
    public function store(Request $request)
    {
        $role = new Role();
        $role->name = $request->role_name;
        $role->slug = Str::of($request->role_name)->slug('-');
        $role->permissions = json_encode($request->permission);
        $role->save();

        return redirect()->route('role.index')->with('success', 'Role added successfully.');
    }
    
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', ['role' => $role]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->role_name;
        $role->slug = Str::of($request->role_name)->slug('-');
        $role->permissions = json_encode($request->permission);
        $role->save();

        return redirect()->route('role.index')->with('success', 'Role Updated Successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
    }

}
