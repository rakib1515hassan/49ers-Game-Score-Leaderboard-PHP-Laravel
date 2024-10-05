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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('cascade');
            
            $table->string('f_name');
            $table->string('l_name');
            $table->string('email');
            $table->string('position')->nullable();
            $table->string('height')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('age')->nullable();
            $table->integer('experience')->nullable();
            $table->string('college')->nullable();
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
