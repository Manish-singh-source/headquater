<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackagingController extends Controller
{
    //
    public function packagingList()
    {
        return view('packagingList.packaging-list');
    }

    public function packingProductsList()
    {
        return view('packagingList.packing-products-list');
    }

    public function readyToShip()
    {
        return view('readyToShip.ready-to-ship');
    }

    public function readyToShipDetail()
    {
        return view('readyToShip.ready-to-ship-detail');
    }

    public function trackOrder()
    {
        return view('trackOrder.track-order');
    }
}
