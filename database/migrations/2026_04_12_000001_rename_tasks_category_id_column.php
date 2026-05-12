<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Legacy tasks migration used Category_id; Eloquent expects category_id for belongsTo(Category::class).
     */
    public function up(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        if (Schema::hasColumn('tasks', 'Category_id') && ! Schema::hasColumn('tasks', 'category_id')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->renameColumn('Category_id', 'category_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        if (Schema::hasColumn('tasks', 'category_id') && ! Schema::hasColumn('tasks', 'Category_id')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->renameColumn('category_id', 'Category_id');
            });
        }
    }
};
