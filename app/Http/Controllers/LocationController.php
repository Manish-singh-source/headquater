<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Get all countries
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountries()
    {
        try {
            $countries = Country::all();

            if ($countries->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Countries retrieved successfully',
                    'data' => $countries,
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'No countries found',
                'data' => [],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving countries: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get states by country ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStates(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'countryId' => 'required|integer|exists:countries,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $states = State::where('country_id', $request->countryId)->get();

            if ($states->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'States retrieved successfully',
                    'data' => $states,
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'No states found for the given country',
                'data' => [],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving states: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get cities by state ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stateId' => 'required|integer|exists:states,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $cities = City::where('state_id', $request->stateId)->get();

            if ($cities->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Cities retrieved successfully',
                    'data' => $cities,
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'No cities found for the given state',
                'data' => [],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving cities: '.$e->getMessage(),
            ], 500);
        }
    }
}
