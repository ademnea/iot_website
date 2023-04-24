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
        Schema::create('website_content', function (Blueprint $table) {
            $table->id();
            $table->text('mission')->nullable();
            $table->text('team_leader_word')->nullable();
            $table->text('objectives')->nullable();
            $table->string('motto')->nullable();
            $table->string('logo')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('company_telephone')->nullable();
            $table->string('company_email')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_content');
    }
};
