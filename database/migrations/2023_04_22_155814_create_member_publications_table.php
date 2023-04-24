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
        Schema::create('member_publications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('publication_id');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_publications');
    }
};
