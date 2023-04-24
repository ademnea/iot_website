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
        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('project_id');
            $table->String('CV');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('applicant_id')->references('id')->on('applicants')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_applications');
    }
};
