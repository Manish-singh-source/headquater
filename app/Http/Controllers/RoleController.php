<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $roles = Role::with('permissions')
                ->withCount('users')
                ->latest()
                ->paginate(15);

            return view('roles.index', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving roles: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new role
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $permissions = Permission::orderBy('name')
                ->get();

            return view('roles.create', compact('permissions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading permissions: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created role in database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => trim($request->name),
                'guard_name' => 'web',
            ]);

            if ($request->has('permissions') && !empty($request->permissions)) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($role)
                ->causedBy(Auth::user())
                ->withProperties([
                    'permissions_count' => count($request->permissions ?? []),
                ])
                ->event('created')
                ->log('Role created: ' . $role->name);

            return redirect()->route('role.index')
                ->with('success', 'Role "' . $role->name . '" created successfully with ' . count($request->permissions ?? []) . ' permission(s).');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error creating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified role
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:roles,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('role.index')->with('error', 'Role not found.');
            }

            $role = Role::with('permissions')
                ->findOrFail($id);

            $rolePermissions = $role->permissions->pluck('id')->toArray();

            $permissions = Permission::orderBy('name')
                ->get()
                ->groupBy(function ($permission) {
                    return explode('.', $permission->name)[0] ?? 'Other';
                });

            return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
        } catch (\Exception $e) {
            return redirect()->route('role.index')
                ->with('error', 'Error loading role: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified role in database
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);

            if (!$role) {
                return redirect()->route('role.index')->with('error', 'Role not found.');
            }

            $oldAttributes = $role->getOriginal();
            $oldPermissions = $role->permissions->pluck('id')->toArray();

            $role->name = trim($request->name);
            $role->save();

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions ?? []);
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($role)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old_name' => $oldAttributes['name'],
                    'new_name' => $role->name,
                    'old_permissions' => $oldPermissions,
                    'new_permissions' => $role->permissions->pluck('id')->toArray(),
                ])
                ->event('updated')
                ->log('Role updated: ' . $role->name);

            return redirect()->route('role.index')
                ->with('success', 'Role "' . $role->name . '" updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified role from database
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('role.index')->with('error', 'Role not found.');
        }

        DB::beginTransaction();

        try {
            $role = Role::findOrFail($id);

            if (!$role) {
                return redirect()->route('role.index')->with('error', 'Role not found.');
            }

            // Check if role has users assigned
            $usersCount = $role->users()->count();
            if ($usersCount > 0) {
                return redirect()->route('role.index')
                    ->with('error', 'Cannot delete role "' . $role->name . '". This role is assigned to ' . $usersCount . ' user(s).');
            }

            $roleName = $role->name;

            // Log activity before deletion
            activity()
                ->performedOn($role)
                ->causedBy(Auth::user())
                ->withProperties([
                    'name' => $role->name,
                    'permissions_count' => $role->permissions()->count(),
                ])
                ->event('deleted')
                ->log('Role deleted: ' . $roleName);

            $role->delete();

            DB::commit();

            return redirect()->route('role.index')
                ->with('success', 'Role "' . $roleName . '" deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('role.index')
                ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple roles
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid role IDs selected.');
        }

        DB::beginTransaction();

        try {
            $ids = $request->ids;
            $roles = Role::whereIn('id', $ids)->get();

            $deletedCount = 0;
            $skippedCount = 0;

            foreach ($roles as $role) {
                $usersCount = $role->users()->count();

                if ($usersCount > 0) {
                    $skippedCount++;
                    continue;
                }

                $roleName = $role->name;

                activity()
                    ->performedOn($role)
                    ->causedBy(Auth::user())
                    ->withProperties(['name' => $role->name])
                    ->event('deleted')
                    ->log('Role deleted: ' . $roleName);

                $role->delete();
                $deletedCount++;
            }

            DB::commit();

            $message = "Deleted: {$deletedCount} role(s)";
            if ($skippedCount > 0) {
                $message .= ", Skipped: {$skippedCount} (roles with assigned users)";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting roles: ' . $e->getMessage());
        }
    }
}
