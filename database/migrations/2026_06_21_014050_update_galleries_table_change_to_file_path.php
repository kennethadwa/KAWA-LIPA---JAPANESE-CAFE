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
    Schema::table('galleries', function (Blueprint $table) {
        // 1. Safe check: If image_path exists, rename it. 
        // If it doesn't exist, we assume it's already file_path or needs to be added.
        if (Schema::hasColumn('galleries', 'image_path')) {
            $table->renameColumn('image_path', 'file_path');
        } elseif (!Schema::hasColumn('galleries', 'file_path')) {
            $table->string('file_path');
        }
        
        // 2. Safe check: Only add media_type if it doesn't exist yet
        if (!Schema::hasColumn('galleries', 'media_type')) {
            $table->string('media_type')->default('image');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function shadow_down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Revert changes if we ever roll back this specific migration
            $table->renameColumn('file_path', 'image_path');
            $table->dropColumn('media_type');
        });
    }
};