<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recipe', function (Blueprint $table) {
            $table->text('description')->change();
            $table->text('ingredients')->change();
        });

        Schema::rename('recipe', 'recipes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('recipes', 'recipe');

        Schema::table('recipe', function (Blueprint $table) {
            $table->string('description')->change();
            $table->string('ingredients')->change();
        });
    }
};
