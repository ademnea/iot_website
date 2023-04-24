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
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('scholarship_id');
            $table->String('CV');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
            $table->foreign('scholarship_id')->references('id')->on('scholarships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_applications');
    }
};
