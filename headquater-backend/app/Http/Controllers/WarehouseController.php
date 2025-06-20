<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    //
    public function warehouseList() {
        return view('warehouse');
    }

    public function createWarehouse() {
        return view('create-warehouse');
    }

    public function warehouseDetail() {
        return view('warehouse-detail');
    }
}
