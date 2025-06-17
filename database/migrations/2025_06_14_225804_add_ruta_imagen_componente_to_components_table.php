<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('components') 
            && ! Schema::hasColumn('components', 'rutaImagenComponente')) {
            
            Schema::table('components', function (Blueprint $table) {
                // añadimos la columna para almacenar la ruta de la imagen
                $table->string('rutaImagenComponente')
                      ->nullable()
                      ->after('contentComponente');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('components', 'rutaImagenComponente')) {
            Schema::table('components', function (Blueprint $table) {
                $table->dropColumn('rutaImagenComponente');
            });
        }
    }
};
