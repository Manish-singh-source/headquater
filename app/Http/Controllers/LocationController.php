<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function getCountries()
    {
        $countries = Country::get();

        if (isset($countries)) {
            return response()->json([
                'status' => true,
                'message' => 'Countries retrieved successfully',
                'data' => $countries,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Countries Not Found',
        ], 400);
    }

    //
    public function getStates(Request $request)
    {
        $states = State::where('country_id', $request->countryId)->get();

        if (isset($states)) {
            return response()->json([
                'status' => true,
                'message' => 'States retrieved successfully',
                'data' => $states,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'States Not Found',
        ], 400);
    }

    //
    public function getCities(Request $request)
    {
        $city = City::where('state_id', $request->stateId)->get();

        if (isset($city)) {
            return response()->json([
                'status' => true,
                'message' => 'City retrieved successfully',
                'data' => $city,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'City Not Found',
        ], 400);
    }
}
