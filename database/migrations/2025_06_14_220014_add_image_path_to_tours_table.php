<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tours') 
            && ! Schema::hasColumn('tours', 'image_path')) {
            
            Schema::table('tours', function (Blueprint $table) {
                $table->string('image_path')
                      ->nullable()
                      ->after('visibility_period');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tours', 'image_path')) {
            Schema::table('tours', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }
};
