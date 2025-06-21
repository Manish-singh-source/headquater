<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{
    //Staff
    public function staffList() {
        return view ('staff');
    }

    public function addStaff() {
        return view ('add-staff');
    }

    public function staffDetail(){
        return view ('staff-detail');
    }


    //Role
    public function roleList(){
        return view ('role');
    }

    public function addRole(){
        return view ('add-role');
    }
}
