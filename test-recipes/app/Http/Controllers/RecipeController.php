<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;

class RecipeController extends Controller
{
    public function index()
    {
        try {
            $query = Recipe::latest();
            
            if (request()->has('search') && !empty(request('search'))) {
                $search = strtolower(request('search'));
                $query->where(function($q) use ($search) {
                    $q->whereRaw('LOWER(ingredients) LIKE ?', ['%' . $search . '%'])
                      ->orWhereRaw('LOWER(title) LIKE ?', ['%' . $search . '%'])
                      ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $search . '%']);
                });
            }
            
            $recipes = $query->paginate(9)->withQueryString();

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

    public function store(StoreRecipeRequest $request)
    {
        try {
            $recipe = Recipe::create($request->validated());
            
            return response()->json([
                'message' => 'Recette créée avec succès !',
                'recipe' => $recipe
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la création de la recette.'], 500);
        }
    }

    public function update(UpdateRecipeRequest $request, $id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            $recipe->update($request->validated());

            return response()->json([
                'message' => 'Recette modifiée avec succès !',
                'recipe' => $recipe
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la modification de la recette.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            $recipe->delete();
            
            return response()->json(['message' => 'Recette supprimée avec succès !'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la suppression de la recette.'], 500);
        }
    }
}
