<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function loginCustomer()
    {
        return view('auth.login');
    }

    public function registerCustomer()
    {
        return view('auth.register');
    }

    public function registerCustomerData(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'firstName' => 'required|min:3',
                'lastName' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'phoneNo' => 'required|digits:10',
                'password' => 'required|confirmed|min:5',
            ]
        );

        if ($validator->fails()) {
            return $validator->failed();
            return redirect()->route('register')->withErrors($validator);
        }

        $register = new User();
        $register->firstName = $request->firstName;
        $register->lastName = $request->lastName;
        $register->email = $request->email;
        $register->phoneNo = $request->phoneNo;
        $register->password = Hash::make($request->password);
        $register->save();

        return redirect()->route('login');
    }

    public function loginAuthCheckCustomerData(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|min:3',
                'password' => 'required|min:5',
            ]
        );

        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('index');
        }

        // flash()->success('Item added to cart Successfully');
        return redirect()->back()->with('error', 'Login credentials failed');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
