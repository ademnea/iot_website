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
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('position');
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->unsignedBigInteger('registered_by')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('registered_by')->references('id')->on('members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
