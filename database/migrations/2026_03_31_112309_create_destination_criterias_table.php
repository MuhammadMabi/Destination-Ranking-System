<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('destination_criterias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->uuid('destination_id');
            $table->uuid('criteria_id');
            $table->decimal('value', 8, 3);

            $table->unique(['destination_id', 'criteria_id']);
            $table->foreign('destination_id')->references('id')->on('destinations')->cascadeOnDelete();
            $table->foreign('criteria_id')->references('id')->on('criterias')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_criterias');
    }
};
