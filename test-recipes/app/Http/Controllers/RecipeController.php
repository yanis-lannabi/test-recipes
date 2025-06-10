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
        return Inertia::render('Recipes/Recipe', ['recipe' => $recipe]);
    }

    public function store(Request $request)
    {
        \Log::info('Données reçues:', $request->all());

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'ingredients' => 'required|string'
            ]);

            \Log::info('Données validées:', $validated);

            $recipe = Recipe::create($validated);
            
            \Log::info('Recette créée:', $recipe->toArray());

            return redirect()->route('recipes')
                ->with('success', 'Recette créée avec succès !');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Une erreur est survenue lors de la création de la recette.');
        }
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string',
        ]);

        $recipe->update($validated);

        return redirect()->route('recipes')
            ->with('message', 'Recette modifiée avec succès !');
    }

    public function destroy($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            $recipe->delete();
            
            return redirect()->route('recipes')
                ->with('success', 'Recette supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la recette.');
        }
    }
}
