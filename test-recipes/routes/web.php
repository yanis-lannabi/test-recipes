<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('recipes', [RecipeController::class, 'index'])->name('recipes');
Route::get('recipes/{id}', [RecipeController::class, 'show'])->name('recipe');
Route::post('recipes', [RecipeController::class, 'store'])->name('recipe.store');
Route::patch('recipes/{id}', [RecipeController::class, 'update'])->name('recipe.update');
Route::delete('recipes/{id}', [RecipeController::class, 'destroy'])->name('recipe.destroy');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


