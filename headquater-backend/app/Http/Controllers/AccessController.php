<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccessController extends Controller
{
    //Staff
    public function staffList()
    {

        $staffs = Admin::paginate(10);

        return view('accessControl.staff', ['staffs' => $staffs]);
    }

    public function addStaff()
    {
        return view('accessControl.add-staff');
    }

    public function staffDetail($id)
    {

        $staffs = Admin::where('id', $id)->first();
        return view('accessControl.staff-detail', ['staffs' => $staffs]);
    }

    public function deletestaff($id)
    {
        $staff = Admin::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff')->with('success', 'Staff deleted successfully.');
    }

    public function editstaff($id)
    {
        $staff = Admin::findOrFail($id);
        return view('accessControl.edit-staff', ['staff' => $staff]);
    }

    public function updatestaff(Request $request, $id)
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

        $staff = Admin::findOrFail($id);
        $staff->uid = Str::uuid()->toString();
        $staff->role = $request->role;
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
        $staff->user_name = $request->user_name;

        $staff->status = $request->status ?? '1'; // Default to active if not set
        $staff->created_by = Auth::id() ?? '1'; // Assuming you have an authenticated user
        $staff->updated_by =  Auth::id() ?? '1'; // Assuming you have an
        $staff->save();

        return redirect()->route('staff')->with('success', 'Customer updated successfully.');
    }

    //Role
    public function roleList()
    {
        $roles = Role::with('admins')->paginate(10);
        return view('accessControl.role', ['roles' => $roles]);
    }

    public function roleDelete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role')->with('success', 'Role deleted successfully.');
    }

    public function roleEdit($id)
    {
        $role = Role::findOrFail($id);
        return view('accessControl.edit-role', ['role' => $role]);
    }

    public function roleUpdate($id, Request $request)
    {
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
    //Staff Store

    public function storeStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'required|unique:admins,phone',
            'email' => 'required|email|unique:admins,email',
            'permanent_address' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $staff = new Admin();
        $staff->uid = Str::uuid()->toString();
        $staff->role = $request->role;
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
        $staff->user_name = $request->user_name;

        $staff->status = $request->status ?? '1'; // Default to active if not set
        $staff->created_by = Auth::id() ?? '1'; // Assuming you have an authenticated user
        $staff->updated_by =  Auth::id() ?? '1'; // Assuming you have an
        $staff->save();

        return redirect()->route('staff')->with('success', 'Staff added successfully.');
    }
}
