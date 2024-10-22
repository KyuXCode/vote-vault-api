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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('model_number');
            $table->string('description');
            $table->date('application_date');
            $table->date('certification_date');
            $table->date('expiration_date');
            $table->string('federal_certification_number')->nullable();
            $table->date('federal_certification_date')->nullable();
            $table->enum('type', ['Certification', 'Reevaluation', 'Renewal', 'Recertification', 'Other']);
            $table->enum('action', ['Approved', 'Pending', 'Denied', 'Other']);
            $table->enum('system_type', ['VS', 'EPB']);
            $table->enum('system_base', ['DRE', 'OpScan', 'PC/Laptop', 'Tablet', 'Custom Hardware', 'Other']);
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
