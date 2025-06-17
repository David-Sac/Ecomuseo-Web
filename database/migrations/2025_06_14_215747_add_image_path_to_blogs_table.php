<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blogs') 
            && ! Schema::hasColumn('blogs', 'image_path')) {
            
            Schema::table('blogs', function (Blueprint $table) {
                $table->string('image_path')
                      ->nullable()
                      ->after('content');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('blogs', 'image_path')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }
};
