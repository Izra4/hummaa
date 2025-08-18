<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add mode column to tryout_results if table and column don't exist
        if (Schema::hasTable('tryout_results') && !Schema::hasColumn('tryout_results', 'mode')) {
            Schema::table('tryout_results', function (Blueprint $table) {
                $table->enum('mode', ['tryout', 'belajar'])->default('tryout')->after('status');
            });
        }

        // Add image_path column to questions if table and column don't exist
        if (Schema::hasTable('questions') && !Schema::hasColumn('questions', 'image_path')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->string('image_path')->nullable()->after('pembahasan');
            });
        }

        // Create material_progress table for tracking user progress
        if (!Schema::hasTable('material_progress')) {
            Schema::create('material_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('material_id')->constrained('materials', 'materi_id')->onDelete('cascade');
                $table->integer('progress_percentage')->default(0);
                $table->timestamp('last_accessed_at')->nullable();
                $table->timestamps();
                
                $table->unique(['user_id', 'material_id']);
            });
        }

        // Create notifications table if not exists (for database notifications)
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                
                $table->index(['notifiable_type', 'notifiable_id']);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('tryout_results') && Schema::hasColumn('tryout_results', 'mode')) {
            Schema::table('tryout_results', function (Blueprint $table) {
                $table->dropColumn('mode');
            });
        }

        if (Schema::hasTable('questions') && Schema::hasColumn('questions', 'image_path')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }

        Schema::dropIfExists('material_progress');
        Schema::dropIfExists('notifications');
    }
};