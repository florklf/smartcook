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
        Schema::create('notations', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('recipe_id')->constrained();
            $table->integer('notation');
            $table->timestamps();
            $table->text('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notations', function (Blueprint $table) {
            $table->dropIfExists('notations');
        });
    }
};
