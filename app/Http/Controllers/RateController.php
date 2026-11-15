<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rate;

class RateController extends Controller
{
    // Show rate update form
    public function create()
    {
        $latestRate = Rate::latest()->first();

        $today22 = $latestRate->today22 ?? 0;
        $today24 = $latestRate->today24 ?? 0;
        $silver_cost = $latestRate->silver_cost ?? 0;

        return view('rate', compact(
            'today22',
            'today24',
            'silver_cost'
        ));
    }

    // Fetch gold and silver rates from SnapData
    public function fetchRates()
    {
        try {
            $goldResponse = Http::timeout(15)
                ->retry(2, 300)
                ->get('https://snapdata.dev/api/v1/gold/in/latest.json');

            $silverResponse = Http::timeout(15)
                ->retry(2, 300)
                ->get('https://snapdata.dev/api/v1/silver/in/latest.json');

            $goldResponse->throw();
            $silverResponse->throw();

            $goldData = $goldResponse->json();
            $silverData = $silverResponse->json();

            // Find a directly observed gold price
            $getPrice = function ($data, $instrumentId) {

                $item = collect($data['observations'] ?? [])
                    ->first(function ($observation) use ($instrumentId) {
                        return ($observation['instrument_id'] ?? '') === $instrumentId
                            && isset($observation['value'])
                            && $observation['value'] !== null
                            && ($observation['filled_from'] ?? null) === null;
                    });

                if (!$item || !is_numeric($item['value']) || $item['value'] <= 0) {
                    throw new \Exception(
                        "Rate unavailable for {$instrumentId}"
                    );
                }

                return [
                    'value' => (float) $item['value'],
                    'date' => $item['date'] ?? null,
                    'status' => $item['status'] ?? 'unknown'
                ];
            };

            $gold22 = $getPrice($goldData, 'XAU.22K.INR.G');
            $gold24 = $getPrice($goldData, 'XAU.24K.INR.G');
            $silver = $getPrice($silverData, 'XAG.INR.KG');

            // Convert silver price from 1 KG to 1 Gram
            $silver['value'] = $silver['value'] / 1000;

            // Prevent displaying obviously invalid data
            foreach ([$gold22, $gold24, $silver] as $rate) {
                if (!in_array($rate['status'], ['final', 'provisional'])) {
                    throw new \Exception('The API has not provided a confirmed rate.');
                }
            }

            return response()->json([
                'success' => true,
                'today22' => $gold22['value'],
                'today24' => $gold24['value'],
                'silver_cost' => $silver['value'],
                'gold_date' => $gold22['date'],
                'silver_date' => $silver['date'],
                'message' => 'Rates fetched successfully.'
            ]);

        } catch (\Throwable $e) {

            \Log::error('Metal rate API error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch rates. Please try again or enter rates manually.'
            ], 502);
        }
    }

    // Save submitted rates
    public function store(Request $request)
    {
        $request->validate([
            'today22' => 'required|numeric|min:1',
            'today24' => 'required|numeric|min:1',
            'silver_cost' => 'required|numeric|min:1',
        ]);

        Rate::create([
            'today22' => $request->today22,
            'today24' => $request->today24,
            'silver_cost' => $request->silver_cost,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rate updated successfully.',
            ]);
        }

        return redirect()
            ->route('stdinfo')
            ->with('success', 'Rate updated successfully!');
    }

    // Dashboard
    public function index()
    {
        $latestRate = Rate::latest()->first();

        $today22 = $latestRate->today22 ?? 0;
        $today24 = $latestRate->today24 ?? 0;
        $silver_cost = $latestRate->silver_cost ?? 0;

        return view('stdinfo', compact(
            'today22',
            'today24',
            'silver_cost'
        ));
    }
}