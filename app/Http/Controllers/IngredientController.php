<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 

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
                'usage_rate' => $usageRate,
                'current_stock' => $ingredient->stock,
                'status' => $ingredient->stock == 0 ? 'Out of Stock' : 
                            ($ingredient->stock <= $ingredient->alert_threshold ? 'Low Stock' : 'In Stock')
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
    
    // Get simplified usage data without stock histories
    $usageData = [];
    $ingredients = Ingredient::all();

    $totalIngredients = $ingredients->count();
    
    // FIX: Properly count low stock ingredients
    $lowStockCount = $ingredients->filter(function($ingredient) {
        return $ingredient->stock <= $ingredient->alert_threshold && $ingredient->stock > 0;
    })->count();
    
    $outOfStockCount = $ingredients->where('stock', 0)->count();

    foreach ($ingredients as $ingredient) {
        // Calculate total used in period
        $totalUsed = $ingredient->getUsageInPeriod($startDate, $endDate);
        
        // Calculate usage rate per day
        $daysInPeriod = $startDate->diffInDays($endDate) + 1;
        $usageRatePerDay = $daysInPeriod > 0 ? round($totalUsed / $daysInPeriod, 2) : 0;
        
        // Determine status
        $status = $ingredient->stock == 0 ? 'Out of Stock' : 
                 ($ingredient->stock <= $ingredient->alert_threshold ? 'Low Stock' : 'In Stock');

        $usageData[] = [
            'ingredient' => $ingredient,
            'total_used' => $totalUsed,
            'current_stock' => $ingredient->stock,
            'alert_threshold' => $ingredient->alert_threshold,
            'usage_rate_per_day' => $usageRatePerDay,
            'status' => $status,
            'days_in_period' => $daysInPeriod
        ];
    }

    // Sort by most used ingredients first
    usort($usageData, function($a, $b) {
        return $b['total_used'] <=> $a['total_used'];
    });

    $data = [
        'usage_data' => $usageData,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'total_ingredients' => $totalIngredients,
        'low_stock_count' => $lowStockCount,
        'out_of_stock_count' => $outOfStockCount,
        'generated_at' => now(),
        'period_days' => $startDate->diffInDays($endDate) + 1
    ];

    $fileName = 'ingredient-usage-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf';

    return Pdf::loadView('ingredients.usage-pdf', $data)
             ->setPaper('a4', 'landscape')
             ->download($fileName);
}
   public function stockHistory(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date'
    ]);

    $startDate = Carbon::parse($request->start_date)->startOfDay();
    $endDate = Carbon::parse($request->end_date)->endOfDay();
    
    $stockHistoryData = [];
    $ingredients = Ingredient::all();

    foreach ($ingredients as $ingredient) {
        $movements = $ingredient->getStockMovementsInPeriod($startDate, $endDate);
        
        if ($movements->count() > 0) {
            foreach ($movements as $movement) {
                $stockHistoryData[] = [
                    'date' => $movement->created_at->format('M j, Y g:i A'),
                    'ingredient_name' => $ingredient->name,
                    'change_type' => $this->getChangeTypeLabel($movement->change_type),
                    'previous_stock' => $movement->previous_stock,
                    'new_stock' => $movement->new_stock,
                    'change_amount' => $movement->change_amount,
                    'reason' => $movement->reason,
                    'user' => $movement->user ? $movement->user->name : 'System',
                    'order_id' => $movement->order_id
                ];
            }
        }
    }

    // Sort by date descending
    usort($stockHistoryData, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    return response()->json([
        'stock_history' => $stockHistoryData,
        'start_date' => $startDate->format('M j, Y'),
        'end_date' => $endDate->format('M j, Y'),
        'total_movements' => count($stockHistoryData)
    ]);
}

public function exportStockHistory(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date'
    ]);

    $startDate = Carbon::parse($request->start_date)->startOfDay();
    $endDate = Carbon::parse($request->end_date)->endOfDay();
    
    $stockHistoryData = [];
    $ingredients = Ingredient::all();

    foreach ($ingredients as $ingredient) {
        $movements = $ingredient->getStockMovementsInPeriod($startDate, $endDate);
        
        if ($movements->count() > 0) {
            foreach ($movements as $movement) {
                $stockHistoryData[] = [
                    'date' => $movement->created_at->format('M j, Y g:i A'),
                    'ingredient' => $ingredient,
                    'movement' => $movement,
                    'change_type' => $this->getChangeTypeLabel($movement->change_type),
                    'user' => $movement->user ? $movement->user->name : 'System'
                ];
            }
        }
    }

    // Sort by date descending
    usort($stockHistoryData, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    $data = [
        'stock_history' => $stockHistoryData,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'total_movements' => count($stockHistoryData),
        'generated_at' => now()
    ];

    $fileName = 'stock-history-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf';

    return Pdf::loadView('ingredients.stock-history-pdf', $data)
             ->setPaper('a4', 'landscape')
             ->download($fileName);
}

private function getChangeTypeLabel($changeType)
{
    $labels = [
        'order_deduction' => 'Order Deduction',
        'manual_update' => 'Manual Update',
        'restock' => 'Restock',
        'initial_stock' => 'Initial Stock'
    ];
    
    return $labels[$changeType] ?? $changeType;
}
}