<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Staff;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\StaffCredentialsMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    //
    public function index()
    {
        $staffs = Staff::with('role')->get();
        return view('staffs.index', ['staffs' => $staffs]);
    }

    public function create()
    {
        $roles = Role::get();
        return view('staffs.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required|digits:10',
            'password' => 'required',
            'email' => 'required|email|unique:staff,email',
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

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $staff = new Staff();
        $staff->uid = Str::uuid()->toString();
        $staff->role_id = $request->role;
        $staff->user_name = $request->user_name;
        $staff->fname = $request->fname;
        $staff->lname = $request->lname;
        $staff->password = $request->password;
        $staff->phone = $request->phone;
        $staff->dob = $request->dob;
        $staff->marital = $request->marital;
        $staff->gender = $request->gender;
        $staff->email = $request->email;
        $staff->current_address  = $request->current_address;
        $staff->permanent_address = $request->permanent_address;
        $staff->country = $request->country;
        $staff->state = $request->state;
        $staff->city = $request->city;
        $staff->pincode = $request->pincode;

        $staff->status = $request->status ?? '1'; // Default to active if not set
        $staff->created_by = Auth::id() ?? '1'; // Assuming you have an authenticated user
        $staff->updated_by =  Auth::id() ?? '1'; // Assuming you have an
        $staff->save();

        // Send email with credentials
         Mail::to($staff->email)->send(new StaffCredentialsMail($staff->email, $request->password));

        return redirect()->route('staff.index')->with('success', 'Staff added successfully.');
    }

    public function edit($id)
    {
        $staff = Staff::with('role')->findOrFail($id);
        $roles = Role::get();
        return view('staffs.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'require',
            'email' => 'required',
            'permanent_address' => 'required',
        ]);


        if ($validator->failed()) {
            DD($validator->errors());
            return back()->withErrors($validator)->withInput();
        }

        $staff = Staff::findOrFail($id);
        $staff->uid = Str::uuid()->toString();
        $staff->role_id = $request->role;
        $staff->user_name = $request->user_name;
        $staff->fname = $request->fname;
        $staff->lname = $request->lname;
        $staff->phone = $request->phone;
        $staff->dob = $request->dob;
        $staff->marital = $request->marital;
        $staff->gender = $request->gender;
        $staff->email = $request->email;
        $staff->current_address  = $request->current_address;
        $staff->permanent_address = $request->permanent_address;
        $staff->city = $request->city;
        $staff->state = $request->state;
        $staff->country = $request->country;
        $staff->pincode = $request->pincode;

        $staff->status = $request->status ?? '1'; // Default to active if not set
        $staff->created_by = Auth::id() ?? '1'; // Assuming you have an authenticated user
        $staff->updated_by =  Auth::id() ?? '1'; // Assuming you have an
        $staff->save();

        return redirect()->route('staff.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully.');
    }

    public function view($id)
    {
        $staffs = Staff::where('id', $id)->first();
        return view('staffs.view', ['staffs' => $staffs]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);
        Staff::destroy($ids);
        return redirect()->back()->with('success', 'Selected Staff deleted successfully.');
    }
    
    public function toggleStatus(Request $request)
    {
        $staff = Staff::findOrFail($request->id);
        $staff->status = $request->status;
        $staff->save();

        return response()->json(['success' => true]);
    }
}
