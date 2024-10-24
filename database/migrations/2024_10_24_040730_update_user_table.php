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
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('npm'); // Menghapus kolom npm
            $table->enum('jurusan', ['fisika', 'kimia', 'biologi', 'matematika', 'ilmu komputer']);
            $table->integer('semester')->unsigned()->default(1)->check('semester <= 14'); // Membatasi semester sampai 14
            $table->foreignId('fakultas_id')->constrained('fakultas'); // Relasi ke tabel fakultas
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
