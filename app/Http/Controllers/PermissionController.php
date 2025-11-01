<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $permissions = Permission::latest()->paginate(15);

            return view('permissions.index', compact('permissions'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving permissions: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new permission
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created permission in database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => 'web',
            ]);

            if ($permission) {
                // Log activity
                activity()
                    ->performedOn($permission)
                    ->causedBy(Auth::user())
                    ->withProperties(['attributes' => $permission->toArray()])
                    ->event('created')
                    ->log('Permission created');

                DB::commit();

                return redirect()->route('permission.index')
                    ->with('success', 'Permission "' . $permission->name . '" created successfully.');
            }

            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to create permission.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error creating permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified permission
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $permission = Permission::findOrFail($id);

            return view('permissions.edit', compact('permission'));
        } catch (\Exception $e) {
            return redirect()->route('permission.index')
                ->with('error', 'Permission not found.');
        }
    }

    /**
     * Update the specified permission in database
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:permissions,name,' . $id,
                'description' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $oldAttributes = $permission->getOriginal();

            $permission->update([
                'name' => $request->name,
            ]);

            if ($permission) {
                // Log activity
                activity()
                    ->performedOn($permission)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'old' => $oldAttributes,
                        'new' => $permission->getChanges(),
                    ])
                    ->event('updated')
                    ->log('Permission updated');

                DB::commit();

                return redirect()->route('permission.index')
                    ->with('success', 'Permission "' . $permission->name . '" updated successfully.');
            }

            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to update permission.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating permission: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified permission from database
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permissionName = $permission->name;

            DB::beginTransaction();

            // Check if permission is assigned to any roles
            $rolesCount = $permission->roles()->count();

            if ($rolesCount > 0) {
                DB::rollBack();

                return redirect()->route('permission.index')
                    ->with('error', 'Cannot delete permission. It is assigned to ' . $rolesCount . ' role(s).');
            }

            $permission->delete();

            // Log activity
            activity()
                ->performedOn($permission)
                ->causedBy(Auth::user())
                ->withProperties(['attributes' => $permission->toArray()])
                ->event('deleted')
                ->log('Permission deleted');

            DB::commit();

            return redirect()->route('permission.index')
                ->with('success', 'Permission "' . $permissionName . '" deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('permission.index')
                ->with('error', 'Error deleting permission: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple permissions
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid permission IDs selected.');
        }

        DB::beginTransaction();

        try {
            $ids = $request->ids;
            $permissions = Permission::whereIn('id', $ids)->get();

            // Check if any permission is assigned to roles
            foreach ($permissions as $permission) {
                $rolesCount = $permission->roles()->count();

                if ($rolesCount > 0) {
                    DB::rollBack();

                    return redirect()->back()
                        ->with('error', 'Cannot delete permission "' . $permission->name . '". It is assigned to roles.');
                }
            }

            $deleted = Permission::whereIn('id', $ids)->delete();

            if ($deleted) {
                DB::commit();

                return redirect()->back()
                    ->with('success', $deleted . ' permission(s) deleted successfully.');
            }

            DB::rollBack();

            return redirect()->back()->with('error', 'No permissions deleted.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Error deleting permissions: ' . $e->getMessage());
        }
    }
}
