<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();
        return view('ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('ingredients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'stock' => 'required|numeric|min:0',
            'alert_threshold' => 'nullable|numeric|min:0'
        ]);

        Ingredient::create($request->all());
        return redirect()->route('ingredients.index')->with('success', 'Ingredient added successfully!');
    }

    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'stock' => 'required|numeric|min:0',
            'alert_threshold' => 'nullable|numeric|min:0'
        ]);

        $ingredient->update($request->all());
        return redirect()->route('ingredients.index')->with('success', 'Ingredient updated successfully!');
    }

    public function destroy(Ingredient $ingredient)
    {
        // Prevent deletion if ingredient is used in any products
        if ($ingredient->products()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete ingredient - it is being used by one or more products.');
        }

        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'Ingredient deleted successfully!');
    }
    public function usageReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        
        $usageData = [];
        $ingredients = Ingredient::all();

        foreach ($ingredients as $ingredient) {
            $totalUsed = $ingredient->getUsageInPeriod($startDate, $endDate);
            $days = $startDate->diffInDays($endDate) + 1;
            $usageRate = $days > 0 ? round($totalUsed / $days, 2) : 0;

            $usageData[] = [
                'ingredient_name' => $ingredient->name,
                'total_used' => $totalUsed,
                'unit' => $ingredient->unit,
                'usage_rate' => $usageRate
            ];
        }

        return response()->json([
            'usage_data' => $usageData,
            'start_date' => $startDate->format('M j, Y'),
            'end_date' => $endDate->format('M j, Y')
        ]);
    }

    public function exportUsage(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ingredient-usage-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['Ingredient', 'Total Used', 'Unit', 'Usage Rate/Day', 'Period']);
            
            $ingredients = Ingredient::all();
            
            foreach ($ingredients as $ingredient) {
                $totalUsed = $ingredient->getUsageInPeriod($startDate, $endDate);
                $days = $startDate->diffInDays($endDate) + 1;
                $usageRate = $days > 0 ? round($totalUsed / $days, 2) : 0;
                
                fputcsv($file, [
                    $ingredient->name,
                    $totalUsed,
                    $ingredient->unit,
                    $usageRate,
                    $startDate->format('M j, Y') . ' to ' . $endDate->format('M j, Y')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}