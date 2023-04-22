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
        Schema::create('member_phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contact')->unique();
            $table->unsignedBigInteger('member_id');
            $table->timestamps();
            
            // Foreign Key Constraints
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_phones');
    }
};
