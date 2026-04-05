<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    //
    public function index()
    {
        // Logic to list staff members
        $staffMembers = User::get(); // Assuming you have a Staff model

        return view('staffs.index', compact('staffMembers'));
    }

    public function create()
    {
        // Logic to show the form for creating a new staff member
        $roles = Role::all(); // Assuming you have a Role model
        $warehouses = Warehouse::all();

        return view('staffs.create', compact('roles', 'warehouses'));
    }

    public function store(Request $request)
    {
        // Logic to store a new staff member
        $validated = Validator::make($request->all(), [
            'warehouse_id' => 'required',
            'role' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required|digits:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'dob' => 'required|date',
            'gender' => 'required',
            'marital' => 'required',
            'current_address' => 'required',
            'permanent_address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required|digits:6',
        ], [
            'warehouse_id.required' => 'The warehouse field is required.',
            'role.required' => 'The role field is required.',
            'fname.required' => 'The first name field is required.',
            'lname.required' => 'The last name field is required.',
            'phone.required' => 'The phone number field is required.',
            'phone.digits' => 'The phone number must be exactly 10 digits.',
            'password.required' => 'The password field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'permanent_address.required' => 'The permanent address field is required.',
            'dob.required' => 'The date of birth field is required.',
            'dob.date' => 'The date of birth must be a valid date.',
        ]);

        if ($validated->fails()) {
            return back()->withErrors($validated)->withInput();
        }

        try {
            $staff = User::create([
                'warehouse_id' => $request->warehouse_id,
                'fname' => $request->fname,
                'lname' => $request->lname,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'dob' => $request->dob,
                'marital' => $request->marital,
                'gender' => $request->gender,
                'email' => $request->email,
                'current_address' => $request->current_address,
                'permanent_address' => $request->permanent_address,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
            ]);

            // Send email with credentials
            //  Mail::to($staff->email)->send(new StaffCredentialsMail($staff->email, $request->password));

            if ($staff) {
                // Assign role to staff
                $staff->roles()->attach($request->role);

                return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
            }

            return back()->with('error', 'Failed to create staff member.')->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the staff member: '.$e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        // Logic to show the form for editing a staff member
        $staff = User::findOrFail($id);
        $roles = Role::all();
        $currentRole = $staff->roles()->first();

        return view('staffs.edit', compact('staff', 'roles', 'currentRole'));
    }

    public function update(Request $request, $id)
    {
        // Logic to update a staff member
        $validated = Validator::make($request->all(), [
            'role' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required|digits:10',
            'email' => 'required|email|unique:users,email,'.$id,
            'dob' => 'required|date',
            'gender' => 'required',
            'marital' => 'required',
            'current_address' => 'required',
            'permanent_address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required|digits:6',
            'status' => 'required',
        ], [
            'role.required' => 'The role field is required.',
            'fname.required' => 'The first name field is required.',
            'lname.required' => 'The last name field is required.',
            'phone.required' => 'The phone number field is required.',
            'phone.digits' => 'The phone number must be exactly 10 digits.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'dob.required' => 'The date of birth field is required.',
            'dob.date' => 'The date of birth must be a valid date.',
            'gender.required' => 'The gender field is required.',
            'marital.required' => 'The marital status field is required.',
            'current_address.required' => 'The current address field is required.',
            'permanent_address.required' => 'The permanent address field is required.',
            'country.required' => 'The country field is required.',
            'state.required' => 'The state field is required.',
            'city.required' => 'The city field is required.',
            'pincode.required' => 'The pincode field is required.',
            'pincode.digits' => 'The pincode must be exactly 6 digits.',
            'status.required' => 'The status field is required.',
        ]);

        if ($validated->fails()) {
            return back()->withErrors($validated)->withInput();
        }

        try {
            $staff = User::findOrFail($id);

            $staff->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'phone' => $request->phone,
                'dob' => $request->dob,
                'marital' => $request->marital,
                'gender' => $request->gender,
                'email' => $request->email,
                'current_address' => $request->current_address,
                'permanent_address' => $request->permanent_address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'pincode' => $request->pincode,
                'status' => $request->status,
            ]);

            // Update role
            $staff->roles()->sync([$request->role]);

            return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the staff member: '.$e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        // Logic to delete a staff member
        $staff = User::findOrFail($id);
        if ($staff->delete()) {
            return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
        }

        return back()->with('error', 'Failed to delete staff member.');
    }

    public function view($id)
    {
        // Logic to view a staff member
        $staff = User::findOrFail($id);
        $staffPermissions = $staff->getAllPermissions();
        $permissionGroups = PermissionGroup::with('permissions')->active()->get();

        if (! $staff) {
            return redirect()->route('staff.index')->with('error', 'Staff member not found.');
        }

        // Assuming you have a view file for displaying staff details
        return view('staffs.view', compact('staff', 'staffPermissions', 'permissionGroups'));
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        $staff = User::destroy($ids);

        return redirect()->back()->with('success', 'Selected Staff deleted successfully.');
    }

    public function toggleStatus(Request $request)
    {
        $staff = User::findOrFail($request->id);
        $staff->status = $request->status;
        $staff->save();

        return response()->json(['success' => true]);
    }
}
