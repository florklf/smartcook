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
        Schema::create('dietary_restrictions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->enum('type', ['allergies', 'diets', 'contraindication']);
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dietary_restrictions', function (Blueprint $table) {
            $table->dropIfExists('dietary_restrictions');
        });
    }
};
