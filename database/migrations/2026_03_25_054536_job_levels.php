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
        Schema::create('job_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Add index for commonly queried fields
            $table->index('slug');
            $table->index('sort_order');
        });
        
        // Seed the job levels based on your select options
        $this->seedJobLevels();
    }
    
    /**
     * Seed the job levels with initial data.
     */
    private function seedJobLevels(): void
    {
        $levels = [
            ['name' => 'Entry Level', 'slug' => 'entry', 'sort_order' => 1],
            ['name' => 'Junior', 'slug' => 'junior', 'sort_order' => 2],
            ['name' => 'Mid Level', 'slug' => 'mid', 'sort_order' => 3],
            ['name' => 'Senior', 'slug' => 'senior', 'sort_order' => 4],
            ['name' => 'Lead', 'slug' => 'lead', 'sort_order' => 5],
            ['name' => 'Manager', 'slug' => 'manager', 'sort_order' => 6],
            ['name' => 'Director', 'slug' => 'director', 'sort_order' => 7],
            ['name' => 'Executive', 'slug' => 'executive', 'sort_order' => 8],
        ];
        
        foreach ($levels as $level) {
            DB::table('job_levels')->insert([
                'name' => $level['name'],
                'slug' => $level['slug'],
                'sort_order' => $level['sort_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_levels');
    }
};