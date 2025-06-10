<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// Route API pour les recettes
Route::prefix('api')->group(function () {
    Route::get('recipes', [RecipeController::class, 'index']);
    Route::get('recipes/{id}', [RecipeController::class, 'show']);
    Route::post('recipes', [RecipeController::class, 'store']);
    Route::put('recipes/{id}', [RecipeController::class, 'update']);
    Route::delete('recipes/{id}', [RecipeController::class, 'destroy']);
});

// Route pour servir la SPA Vue.js
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


