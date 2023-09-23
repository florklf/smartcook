<?php

use App\Enums\DietaryRestrictionTypeEnum;
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
            $table->id();
            $table->enum('type', DietaryRestrictionTypeEnum::values());
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
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
