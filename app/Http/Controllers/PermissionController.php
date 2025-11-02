<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    //
    public function index()
    {
        // Logic to list permissions    
        $permissions = Permission::latest()->get(); // Assuming you have a Permission model
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        // Logic to show create permission form
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        // Logic to store a new permission
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        $Permission = Permission::create($request->all());
        if ($Permission) {
            return redirect()->route('permission.index')->with('success', 'Permission created successfully.');
        }

        return redirect()->back()->with('error', 'Failed to create permission.');
    }

    public function edit($id)
    {
        // Logic to show edit permission form
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        // Logic to update a permission
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update($request->all());

        if($permission) {
            return redirect()->route('permission.index')->with('success', 'Permission updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update permission.');
    }

    public function destroy($id)
    {
        // Logic to delete a permission
        $permission = Permission::findOrFail($id);
        $permission->delete();

        if ($permission) {
            return redirect()->route('permission.index')->with('success', 'Permission deleted successfully.');
        }
        return redirect()->back()->with('error', 'Failed to delete permission.');
    }
}