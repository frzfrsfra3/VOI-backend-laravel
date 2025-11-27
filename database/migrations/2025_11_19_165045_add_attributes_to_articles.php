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
        Schema::table('articles', function (Blueprint $table) {
            // Add new columns
            $table->string('slug')->unique()->after('title');
            $table->text('excerpt')->nullable()->after('content');
            $table->string('featured_image')->nullable()->after('excerpt');
            $table->boolean('is_published')->default(false)->after('featured_image');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->integer('view_count')->default(0)->after('published_at');
            $table->unsignedBigInteger('category_id')->nullable()->after('view_count');
            $table->boolean('comments_enabled')->default(true);
            $table->string('image')->nullable();
            
            // Add foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            
            // Add index for better performance
            $table->index('is_published');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['category_id']);
            
            // Drop indexes
            $table->dropIndex(['is_published']);
            $table->dropIndex(['published_at']);
            
            // Drop columns
            $table->dropColumn([
                'slug',
                'excerpt', 
                'featured_image',
                'is_published',
                'published_at',
                'view_count',
                'category_id'
            ]);
        });
    }
};