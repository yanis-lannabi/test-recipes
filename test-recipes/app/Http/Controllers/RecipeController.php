<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index()
    {
        try {
            $recipes = Recipe::latest()
                ->paginate(9)
                ->withQueryString();

            return response()->json($recipes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des recettes.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            return response()->json($recipe);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Recette non trouvée.'], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'ingredients' => 'required|string'
            ]);

            $recipe = Recipe::create($validated);
            
            return response()->json([
                'message' => 'Recette créée avec succès !',
                'recipe' => $recipe
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la création de la recette.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $recipe = Recipe::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'ingredients' => 'required|string',
            ]);

            $recipe->update($validated);

            return response()->json([
                'message' => 'Recette modifiée avec succès !',
                'recipe' => $recipe
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la modification de la recette.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            $recipe->delete();
            
            return response()->json(['message' => 'Recette supprimée avec succès !'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la suppression de la recette.'], 500);
        }
    }
}
