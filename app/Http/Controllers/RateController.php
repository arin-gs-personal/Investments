<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Rate;

class RateController extends Controller
{
    // Show the rate form
    public function create()
    {
        // Get the latest values from Student
        $today22 = Rate::latest()->value('today22') ?? 0;
        $today24 = Rate::latest()->value('today24') ?? 0;
        $silver_cost = Rate::latest()->value('silver_cost') ?? 0;

        return view('rate', compact('today22', 'today24', 'silver_cost'));
    }

    // Handle form submission
    public function store(Request $request)
    {
        $request->validate([
            'today22'     => 'required|integer|min:1',
            'today24'     => 'required|integer|min:1',
            'silver_cost' => 'required|integer|min:1',
        ]);

        $today22 = $request->today22;
        $today24 = $request->today24;
        $silver_cost = $request->silver_cost;


        Rate::create([
            'today22'     => $today22,
            'today24'     => $today24,
            'silver_cost' => $silver_cost,
        ]);

        return redirect()->route('stdinfo')->with('success', 'Rate updated successfully!');
    }

    // Show the dashboard / stdinfo
    public function index()
    {
        $today22 = Rate::latest()->value('today22') ?? 0;
        $today24 = Rate::latest()->value('today24') ?? 0;
        $silver_cost = Rate::latest()->value('silver_cost') ?? 0;

        

        return view('stdinfo', compact(
            'today22','today24','silver_cost'
        ));
    }
}
