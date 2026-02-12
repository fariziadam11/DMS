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
        Schema::create('document_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tag_id');
            $table->string('document_type');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('tagged_by')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unique(['tag_id', 'document_type', 'document_id'], 'unique_tag_document');
            $table->index(['document_type', 'document_id'], 'idx_document');
            $table->index('tag_id', 'idx_tag');

            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('tagged_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_tags');
    }
};
