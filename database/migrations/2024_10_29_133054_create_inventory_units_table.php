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
        Schema::create('inventory_units', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->date('acquisition_date');
            $table->enum('condition', ['New', 'Excellent', 'Good', 'Worn', 'Damaged', 'Unusable']);
            $table->enum('usage', ['Active', 'Spare', 'Display', 'Other', 'Inactive']);
            $table->foreignId('expense_id')->constrained()->onDelete('cascade');
            $table->foreignId('component_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_units');
    }
};
