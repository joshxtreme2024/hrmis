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
        Schema::create('pds_background_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Question 34
            $table->enum('q34_a', ['yes', 'no'])->nullable();
            $table->text('q34_a_details')->nullable();
            $table->enum('q34_b', ['yes', 'no'])->nullable();
            $table->text('q34_b_details')->nullable();
            
            // Question 35
            $table->enum('q35_a', ['yes', 'no'])->nullable();
            $table->text('q35_a_details')->nullable();
            $table->enum('q35_b', ['yes', 'no'])->nullable();
            $table->text('q35_b_details')->nullable();
            
            // Question 36
            $table->enum('q36', ['yes', 'no'])->nullable();
            $table->text('q36_details')->nullable();
            
            // Question 37
            $table->enum('q37', ['yes', 'no'])->nullable();
            $table->text('q37_details')->nullable();
            
            // Question 38
            $table->enum('q38_a', ['yes', 'no'])->nullable();
            $table->text('q38_a_details')->nullable();
            $table->enum('q38_b', ['yes', 'no'])->nullable();
            $table->text('q38_b_details')->nullable();
            
            // Question 39
            $table->enum('q39', ['yes', 'no'])->nullable();
            $table->text('q39_details')->nullable();
            
            // Question 40
            $table->enum('q40_a', ['yes', 'no'])->nullable();
            $table->text('q40_a_details')->nullable();
            $table->enum('q40_b', ['yes', 'no'])->nullable();
            $table->text('q40_b_details')->nullable();
            $table->enum('q40_c', ['yes', 'no'])->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pds_background_infos');
    }
};
