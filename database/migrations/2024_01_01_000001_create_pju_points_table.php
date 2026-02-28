<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pju_points', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['pju', 'rambu', 'rppj', 'cermin'])->default('pju');
            $table->enum('jenis', ['sonte', 'led', 'kalipucang'])->default('led');
            $table->string('daya')->nullable(); // e.g. 150w, 250w
            $table->enum('letak', ['kiri', 'kanan'])->default('kiri');
            $table->string('type')->nullable(); // e.g. stang 4m
            $table->decimal('lat', 10, 8);
            $table->decimal('long', 11, 8);
            $table->enum('status', ['normal', 'mati'])->default('normal');
            $table->boolean('is_verified')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pju_points');
    }
};
