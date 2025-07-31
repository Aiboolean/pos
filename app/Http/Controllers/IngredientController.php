<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

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
}