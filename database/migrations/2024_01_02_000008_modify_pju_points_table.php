<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pju_points', function (Blueprint $table) {
            // Drop old enum columns
            $table->dropColumn(['kategori', 'jenis']);
        });

        Schema::table('pju_points', function (Blueprint $table) {
            // Add new foreign key columns
            $table->foreignId('category_id')->after('nama')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('pju_type_id')->after('category_id')->constrained('pju_types')->cascadeOnDelete();
            
            // Add wilayah columns
            $table->foreignId('kecamatan_id')->nullable()->after('type')->constrained('kecamatans')->nullOnDelete();
            $table->foreignId('desa_id')->nullable()->after('kecamatan_id')->constrained('desas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pju_points', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['pju_type_id']);
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['desa_id']);
            
            $table->dropColumn(['category_id', 'pju_type_id', 'kecamatan_id', 'desa_id']);
            
            $table->enum('kategori', ['pju', 'rambu', 'rppj', 'cermin'])->default('pju');
            $table->enum('jenis', ['sonte', 'led', 'kalipucang'])->default('led');
        });
    }
};
