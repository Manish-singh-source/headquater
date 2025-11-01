<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $staffMembers = User::with('roles')
                ->where('id', '!=', Auth::id()) // Exclude current user from listing
                ->latest()
                ->paginate(15);

            return view('staffs.index', compact('staffMembers'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving staff members: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new staff member
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $roles = Role::orderBy('name')->get();

            if ($roles->isEmpty()) {
                return redirect()->route('staff.index')
                    ->with('info', 'Please create roles before adding staff members.');
            }

            return view('staffs.create', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created staff member
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|integer|exists:roles,id',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'gender' => 'required|in:male,female,other',
            'marital' => 'required|in:single,married,divorced,widowed',
            'dob' => 'required|date|before:today',
            'permanent_address' => 'required|string|max:500',
            'current_address' => 'required|string|max:500',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|regex:/^[0-9]{6}$/',
        ], [
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'pincode.regex' => 'Pincode must be exactly 6 digits.',
            'dob.before' => 'Date of birth must be in the past.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $staff = User::create([
                'fname' => trim($request->fname),
                'lname' => trim($request->lname),
                'email' => strtolower(trim($request->email)),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
                'marital' => $request->marital,
                'dob' => $request->dob,
                'permanent_address' => trim($request->permanent_address),
                'current_address' => trim($request->current_address),
                'country' => trim($request->country),
                'state' => trim($request->state),
                'city' => trim($request->city),
                'pincode' => $request->pincode,
                'status' => '1', // Active by default
            ]);

            // Assign role to staff
            $staff->assignRole($request->role);

            DB::commit();

            // Log activity
            activity()
                ->performedOn($staff)
                ->causedBy(Auth::user())
                ->withProperties([
                    'fname' => $staff->fname,
                    'email' => $staff->email,
                    'role_id' => $request->role,
                ])
                ->event('created')
                ->log('Staff member created: ' . $staff->fname . ' ' . $staff->lname);

            // TODO: Send email with credentials
            // Mail::to($staff->email)->send(new StaffCredentialsMail($staff));

            return redirect()->route('staff.index')
                ->with('success', 'Staff member "' . $staff->fname . ' ' . $staff->lname . '" created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error creating staff member: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a staff member
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('staff.index')->with('error', 'Staff member not found.');
            }

            $staff = User::with('roles')->findOrFail($id);
            $roles = Role::orderBy('name')->get();

            return view('staffs.edit', compact('staff', 'roles'));
        } catch (\Exception $e) {
            return redirect()->route('staff.index')
                ->with('error', 'Error loading staff member: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified staff member
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|integer|exists:roles,id',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'gender' => 'nullable|in:male,female,other',
            'marital' => 'nullable|in:single,married,divorced,widowed',
            'dob' => 'nullable|date|before:today',
            'permanent_address' => 'required|string|max:500',
            'current_address' => 'required|string|max:500',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pincode' => 'required|regex:/^[0-9]{6}$/',
            'status' => 'nullable|in:0,1',
        ], [
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'pincode.regex' => 'Pincode must be exactly 6 digits.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $staff = User::findOrFail($id);

            if (!$staff) {
                return redirect()->route('staff.index')->with('error', 'Staff member not found.');
            }

            // Prevent editing system admin
            if ($staff->id === 1 && Auth::id() !== 1) {
                return redirect()->back()->with('error', 'You cannot edit the system administrator.');
            }

            $oldAttributes = $staff->getOriginal();

            $staff->update([
                'fname' => trim($request->fname),
                'lname' => trim($request->lname),
                'email' => strtolower(trim($request->email)),
                'phone' => $request->phone,
                'gender' => $request->gender,
                'marital' => $request->marital,
                'dob' => $request->dob,
                'permanent_address' => trim($request->permanent_address),
                'current_address' => trim($request->current_address),
                'country' => trim($request->country),
                'state' => trim($request->state),
                'city' => trim($request->city),
                'pincode' => $request->pincode,
                'status' => $request->status ?? '1',
            ]);

            // Update role
            $staff->syncRoles([$request->role]);

            DB::commit();

            // Log activity
            activity()
                ->performedOn($staff)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old' => $oldAttributes,
                    'new' => $staff->getChanges(),
                    'role_id' => $request->role,
                ])
                ->event('updated')
                ->log('Staff member updated: ' . $staff->fname . ' ' . $staff->lname);

            return redirect()->route('staff.index')
                ->with('success', 'Staff member "' . $staff->fname . ' ' . $staff->lname . '" updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating staff member: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * View staff member details
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('staff.index')->with('error', 'Staff member not found.');
            }

            $staff = User::with('roles')->findOrFail($id);
            $staffPermissions = $staff->getAllPermissions();
            $permissions = Permission::orderBy('name')->get();

            return view('staffs.view', compact('staff', 'staffPermissions', 'permissions'));
        } catch (\Exception $e) {
            return redirect()->route('staff.index')
                ->with('error', 'Error loading staff details: ' . $e->getMessage());
        }
    }

    /**
     * Delete a staff member
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('staff.index')->with('error', 'Staff member not found.');
        }

        DB::beginTransaction();

        try {
            $staff = User::findOrFail($id);

            if (!$staff) {
                return redirect()->route('staff.index')->with('error', 'Staff member not found.');
            }

            // Prevent deleting system admin
            if ($staff->id === 1) {
                return redirect()->back()->with('error', 'You cannot delete the system administrator.');
            }

            // Prevent deleting currently logged-in user
            if ($staff->id === Auth::id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            $staffName = $staff->fname . ' ' . $staff->lname;

            // Log activity before deletion
            activity()
                ->performedOn($staff)
                ->causedBy(Auth::user())
                ->withProperties([
                    'email' => $staff->email,
                    'role_count' => $staff->roles()->count(),
                ])
                ->event('deleted')
                ->log('Staff member deleted: ' . $staffName);

            $staff->delete();

            DB::commit();

            return redirect()->route('staff.index')
                ->with('success', 'Staff member "' . $staffName . '" deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting staff member: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple staff members
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid staff member IDs selected.');
        }

        DB::beginTransaction();

        try {
            $ids = $request->ids;
            $protectedIds = [1, Auth::id()]; // Protect system admin and current user

            // Filter out protected IDs
            $deletableIds = array_diff($ids, $protectedIds);

            if (empty($deletableIds)) {
                return redirect()->back()
                    ->with('error', 'Cannot delete system admin or your own account.');
            }

            $staffMembers = User::whereIn('id', $deletableIds)->get();

            foreach ($staffMembers as $staff) {
                activity()
                    ->performedOn($staff)
                    ->causedBy(Auth::user())
                    ->withProperties(['email' => $staff->email])
                    ->event('deleted')
                    ->log('Staff member deleted (bulk): ' . $staff->fname . ' ' . $staff->lname);
            }

            $deleted = User::destroy($deletableIds);

            DB::commit();

            $message = 'Successfully deleted ' . $deleted . ' staff member(s).';
            $skipped = count($ids) - $deleted;
            if ($skipped > 0) {
                $message .= ' (' . $skipped . ' protected entries skipped)';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error deleting staff members: ' . $e->getMessage());
        }
    }

    /**
     * Toggle staff member status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $staff = User::findOrFail($request->id);

            // Prevent disabling system admin
            if ($staff->id === 1 && $request->status === '0') {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot disable the system administrator.',
                ], 403);
            }

            $oldStatus = $staff->status;
            $staff->status = $request->status;
            $staff->save();

            DB::commit();

            // Log activity
            activity()
                ->performedOn($staff)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $staff->status,
                ])
                ->event('status_changed')
                ->log('Staff member status changed: ' . ($staff->status === '1' ? 'Active' : 'Inactive'));

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
                'status' => $staff->status,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
