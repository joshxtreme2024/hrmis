<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Document Types Table - CSC 201 File Requirements
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('csc_circular')->nullable(); // CSC reference
            $table->timestamps();
        });

        // Employee Documents Table
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('version')->default('1.0');
            $table->date('document_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('issuing_authority')->nullable(); // Who issued the document
            $table->string('received_from')->nullable(); // For transferees
            $table->date('received_date')->nullable();
            $table->json('metadata')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'expired', 'archived'])->default('draft');
            $table->text('remarks')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_confidential')->default(false);
            $table->boolean('is_original')->default(false); // Original or copy
            $table->string('original_location')->nullable(); // Where original is kept
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'document_type_id']);
            $table->index('status');
            $table->index('document_date');
            $table->index('expiry_date');
        });

        // Document Versions Table
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_document_id')->constrained()->cascadeOnDelete();
            $table->string('version');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size')->nullable();
            $table->text('change_notes')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });

        // Document Checklist - Track completeness of 201 file
        Schema::create('document_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->date('completed_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('checked_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('document_checklists');
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('document_types');
    }
};