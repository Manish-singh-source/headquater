<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Validator};
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of permission groups
     */
    public function index()
    {
        $permissionGroups = PermissionGroup::with('permissions')->latest()->get();
        return view('permissions.index', compact('permissionGroups'));
    }

    /**
     * Show the form for creating a new permission group
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created permission group in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:255|unique:permission_groups,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Create permission group
            $permissionGroup = PermissionGroup::create([
                'name' => $request->group_name,
                'description' => $request->description,
                'status' => 1,
            ]);

            // Create permissions and assign to group
            foreach ($request->permissions as $permissionName) {
                if (!empty($permissionName)) {
                    Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'web',
                        'permission_group_id' => $permissionGroup->id,
                    ]);
                }
            }

            DB::commit();
            activity()
                ->performedOn($permissionGroup)
                ->causedBy(Auth::user())
                ->log('Permission group created');

            return redirect()->route('permission.index')->with('success', 'Permission group created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission group creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified permission group
     */
    public function edit($id)
    {
        $permissionGroup = PermissionGroup::with('permissions')->findOrFail($id);
        return view('permissions.edit', compact('permissionGroup'));
    }

    /**
     * Update the specified permission group in storage
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:255|unique:permission_groups,name,' . $id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'required|array|min:1',
            'permissions.*.id' => 'nullable|exists:permissions,id',
            'permissions.*.name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $permissionGroup = PermissionGroup::findOrFail($id);

            // Update group details
            $permissionGroup->update([
                'name' => $request->group_name,
                'description' => $request->description,
            ]);

            // Get existing permission IDs
            $existingPermissionIds = [];

            // Update or create permissions
            foreach ($request->permissions as $permissionData) {
                if (!empty($permissionData['name'])) {
                    if (isset($permissionData['id']) && !empty($permissionData['id'])) {
                        // Update existing permission
                        $permission = Permission::find($permissionData['id']);
                        if ($permission) {
                            $permission->update(['name' => $permissionData['name']]);
                            $existingPermissionIds[] = $permission->id;
                        }
                    } else {
                        // Create new permission
                        $newPermission = Permission::create([
                            'name' => $permissionData['name'],
                            'guard_name' => 'web',
                            'permission_group_id' => $permissionGroup->id,
                        ]);
                        $existingPermissionIds[] = $newPermission->id;
                    }
                }
            }

            // Delete permissions that were removed
            Permission::where('permission_group_id', $permissionGroup->id)
                ->whereNotIn('id', $existingPermissionIds)
                ->delete();

            DB::commit();
            activity()
                ->performedOn($permissionGroup)
                ->causedBy(Auth::user())
                ->log('Permission group updated');

            return redirect()->route('permission.index')->with('success', 'Permission group updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission group update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified permission group from storage
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $permissionGroup = PermissionGroup::findOrFail($id);

            // Delete all permissions in this group
            Permission::where('permission_group_id', $id)->delete();

            // Delete the group
            $permissionGroup->delete();

            DB::commit();
            activity()
                ->performedOn($permissionGroup)
                ->causedBy(Auth::user())
                ->log('Permission group deleted');

            return redirect()->route('permission.index')->with('success', 'Permission group deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission group deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete a single permission from a group
     */
    public function deletePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid permission ID'], 422);
        }

        DB::beginTransaction();
        try {
            $permission = Permission::findOrFail($request->permission_id);
            $permission->delete();

            DB::commit();
            activity()
                ->performedOn($permission)
                ->causedBy(Auth::user())
                ->log('Permission deleted');

            return response()->json(['success' => true, 'message' => 'Permission deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission deletion failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete selected permission groups
     */
    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:permission_groups,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid selection');
        }

        DB::beginTransaction();
        try {
            // Delete all permissions in selected groups
            Permission::whereIn('permission_group_id', $request->ids)->delete();

            // Delete the groups
            PermissionGroup::whereIn('id', $request->ids)->delete();

            DB::commit();
            activity()
                ->causedBy(Auth::user())
                ->log('Multiple permission groups deleted');

            return redirect()->route('permission.index')->with('success', 'Selected permission groups deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk permission group deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Toggle permission group status
     */
    public function toggleStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:permission_groups,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false], 422);
        }

        DB::beginTransaction();
        try {
            $permissionGroup = PermissionGroup::findOrFail($request->id);
            $permissionGroup->status = $request->status;
            $permissionGroup->save();

            DB::commit();
            activity()
                ->performedOn($permissionGroup)
                ->causedBy(Auth::user())
                ->log('Permission group status changed');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Permission group status toggle failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}