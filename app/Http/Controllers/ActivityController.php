<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    //
    public function index()
    {
        $activities = Activity::with('causer', 'subject')->latest()->get();

        return view('activity-logs', compact('activities'));
    }
}
