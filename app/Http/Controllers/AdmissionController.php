<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;

class AdmissionController extends Controller
{
    // Show admission form
    public function create()
    {
        return view('admission');
    }

    // Store new student admission
    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string',
            'dob'          => 'required|date',
            'kind'         => 'required',
            'quantity'     => 'required|integer|min:1',
            'cost'         => 'required|numeric|min:1',
            'metal'        => 'required|string',
            'phone'        => 'required|string',
            'address'      => 'required|string',
        ]);

        $reminder = $request->cost / $request->quantity;

        Student::create([
            'user_id'      => Auth::id(),
            'student_name' => $request->student_name,
            'dob'          => $request->dob,
            'kind'         => $request->kind,
            'quantity'     => $request->quantity,
            'cost'         => $request->cost,
            'reminder'     => $reminder,
            'metal'        => $request->metal,
            'phone'        => $request->phone,
            'address'      => $request->address,
        ]);

        // return redirect()->back()->with('success', 'Admission submitted successfully!');
        return redirect()->route('stdinfo')->with('success', 'Rate updated successfully!');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    // Dashboard / student info
    public function index()
{
    $userId = Auth::id();

    // getn latest rate
    $rate = Rate::latest()->first();
    if (!$rate) {
        return redirect()->back()->with('error', 'Rate not found.');
    }

    // Get the values for today22, today24, and silver_cost from the latest rate
    $today22 = $rate->today22 ?? 0;
    $today24 = $rate->today24 ?? 0;
    $silver_cost = $rate->silver_cost ?? 0;

    // ✅ Fetch students by kind for this user
    $studentsKind22 = Student::where('user_id', $userId)->where('kind', 22)->get();
    $studentsKind24 = Student::where('user_id', $userId)->where('kind', 24)->get();
    $studentsKindSS = Student::where('user_id', $userId)->where('kind', 99)->get();

    // ✅ Fetch students by metal for this user
    $studentsMetal22 = Student::where('user_id', $userId)->where('metal', 'GG')->get();
    $studentsMetalSS = Student::where('user_id', $userId)->where('metal', 'SS')->get();

    // ✅ Totals by kind
    $totalQN22 = $studentsKind22->sum('quantity');
    $totalQN24 = $studentsKind24->sum('quantity');
    $totalSS = $studentsKindSS->sum('quantity');

    $totalCostKind22 = $studentsKind22->sum('cost');
    $totalCostKind24 = $studentsKind24->sum('cost');
    $totalCostKindSS = $studentsKindSS->sum('cost');

    // ✅ Grand totals using latest rates
     $grandTotal22 = $totalQN22 * $rate->today22;
    $grandTotal24 = $totalQN24 * $rate->today24;
    $grandTotalSS = $totalSS * $rate->silver_cost;


    // ✅ Profit/Loss per type
    $totalCost22 = $grandTotal22 - $totalCostKind22;
    $totalCost24 = $grandTotal24 - $totalCostKind24;
    $totalCostSS = $grandTotalSS - $totalCostKindSS;

    // ✅ Total overall (total profit/loss)
    $tt = $totalCost22 + $totalCost24 + $totalCostSS;

    // ✅ Pass all data to the view
    return view('stdinfo', compact(
        'today22', 'today24', 'silver_cost',
        'studentsKind22', 'studentsKind24', 'studentsKindSS',
        'studentsMetal22', 'studentsMetalSS',
        'totalQN22', 'totalQN24', 'totalSS',
        'totalCostKind22', 'totalCostKind24', 'totalCostKindSS',
        'grandTotal22', 'grandTotal24', 'grandTotalSS',
        'totalCost22', 'totalCost24', 'totalCostSS',
        'tt'
    ));
}

}
