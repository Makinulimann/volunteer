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
        Schema::create('blog_category', function (Blueprint $table) {
            $table->id(); // Primary key untuk pivot table
            $table->foreignUuid('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignUuid('category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->timestamps();

            // Mencegah duplikasi data
            $table->unique(['blog_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_category');
    }
};
