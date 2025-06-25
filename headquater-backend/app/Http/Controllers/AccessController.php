<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    //Staff
    public function staffList()
    {
        return view('staff');
    }

    public function addStaff()
    {
        return view('add-staff');
    }

    public function staffDetail()
    {
        return view('staff-detail');
    }


    //Role
    public function roleList()
    {
        $roles = Role::with('admins')->get();
        return view('accessControl.role', ['roles' => $roles]);
    }

    public function roleDelete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        
        return redirect()->route('role')->with('success', 'Role deleted successfully.');
    }
    
    public function roleEdit($id) {
        $role = Role::findOrFail($id);
        // dd($role);
        return view('accessControl.edit-role', ['role' => $role]);
    }

    public function roleUpdate($id, Request $request) {
        $role = Role::findOrFail($id);
        $role->name = $request->role_name;
        $role->slug = Str::of($request->role_name)->slug('-');
        $role->permissions = json_encode($request->permission);
        $role->save();

        return redirect()->route('role')->with('success', 'Role Updated Successfully.');
    }


    public function addRole()
    {
        return view('accessControl.add-role');
    }

    public function storeRole(Request $request)
    {
        $role = new Role();
        $role->name = $request->role_name;
        $role->slug = Str::of($request->role_name)->slug('-');
        $role->permissions = json_encode($request->permission);
        $role->save();

        return redirect()->route('role')->with('success', 'Role added successfully.');
    }
}
