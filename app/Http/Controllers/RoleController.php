<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    public function index()
    {
        // Logic to list roles
        $roles = Role::latest()->get(); // Assuming you have a Role model

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Logic to show create role form
        $permissionGroups = PermissionGroup::with('permissions')->active()->get();

        return view('roles.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        // Logic to store a new role
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }
        if ($role) {
            return redirect()->route('role.index')->with('success', 'Role created successfully.');
        }

        return redirect()->back()->with('error', 'Failed to create role.');

    }

    public function edit($id)
    {
        // Logic to show edit role form
        $role = Role::with('permissions')->findOrFail($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray(); // Get permissions for the role
        $permissionGroups = PermissionGroup::with('permissions')->active()->get();

        return view('roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        // Logic to update a role
        $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
            'permissions' => 'array',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        if ($role) {
            return redirect()->route('role.index')->with('success', 'Role updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update role.');

    }

    public function destroy($id)
    {
        // Logic to delete a role
        $role = Role::findOrFail($id);
        $role->delete();

        if ($role) {
            return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
        }

        return redirect()->back()->with('error', 'Failed to delete role.');
    }
}
