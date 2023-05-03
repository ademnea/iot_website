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
        Schema::create('prototype_photos', function (Blueprint $table) {
            $table->id();
            $table->string('photo');
            $table->unsignedBigInteger('prototype_id');
            $table->timestamps();
            // Foreign key constraints
            $table->foreign('prototype_id')->references('id')->on('prototypes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prototype_photos');
    }
};
