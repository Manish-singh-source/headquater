<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

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
        return view('staffs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Logic to store a new staff member        
        $validated = Validator::make($request->all(), [
            'role' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required|digits:10',
            'password' => 'required',
            'email' => 'required|email|unique:users,email',
            'permanent_address' => 'required',
            'dob' => 'required|date',
            'gender' => 'required',
            'marital' => 'required',
            'current_address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pincode' => 'required|digits:6',
        ]);

        if ($validated->fails()) {
            return back()->withErrors($validated)->withInput();
        }

        $staff = User::create([
            'user_name' => $request->user_name,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'password' => $request->password,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'marital' => $request->marital,
            'gender' => $request->gender,
            'email' => $request->email,
            'current_address'  => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode
        ]);

        // Send email with credentials
        //  Mail::to($staff->email)->send(new StaffCredentialsMail($staff->email, $request->password));

        if ($staff) {
            // Assign role to staff
            $staff->roles()->attach($request->role);
            return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
        }
        return back()->with('error', 'Failed to create staff member.');
    }

    public function edit($id)
    {
        // Logic to show the form for editing a staff member
        $staff = User::findOrFail($id);
        $roles = Role::all();
        return view('staffs.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Logic to update a staff member
        $staff = User::findOrFail($id);
        $validated = Validator::make($request->all(), [
            'role' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'permanent_address' => 'required',
        ]);

        if ($validated->fails()) {
            return back()->withErrors($validated)->withInput();
        }


        $staff->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'marital' => $request->marital,
            'gender' => $request->gender,
            'email' => $request->email,
            'current_address'  => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pincode' => $request->pincode,
            'status' => $request->status ?? '1', // Default to active if not set,
        ]);

        // Update role
        $staff->roles()->sync([$request->role]);

        if ($staff) {
            // Mail::to($staff->email)->send(new StaffCredentialsMail($staff->email, $request->password));
            return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
        }
        return back()->with('error', 'Failed to update staff member.');
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
        $permissions = Permission::all(); // Assuming you have a Permission model

        if (!$staff) {
            return redirect()->route('staff.index')->with('error', 'Staff member not found.');
        }
        // Assuming you have a view file for displaying staff details
        return view('staffs.view', compact('staff', 'staffPermissions', 'permissions'));
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