<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        try {
            $recipes = Recipe::latest()
                ->paginate(9)
                ->withQueryString();

            return Inertia::render('Recipes/Index', [
                'recipes' => $recipes,
                'filters' => request()->only(['search', 'sort']),
                'message' => Recipe::count() === 0 ? 'Aucune recette trouvée.' : null
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la récupération des recettes.');
        }
    }

    public function show($id)
    {
        $recipe = Recipe::findOrFail($id);
        return Inertia::render('Recipes/Show', ['recipe' => $recipe]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string'
        ]);

        $recipe = Recipe::create($validated);
        return redirect()->route('recipes.show', $recipe->id)
            ->with('success', 'Recette créée avec succès !');
    }
}
