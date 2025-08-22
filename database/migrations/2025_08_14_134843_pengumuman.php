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
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengumuman');
            $table->text('deskripsi');
            $table->foreignId('kategoris_id')->constrained('kategoris')->onDelete('cascade');
            $table->json('file_paths')->nullable();
            $table->enum('tipe_pengumuman', [
                'Reminder',
                'Info',
                'Penting',
                'Umum',
                'Darurat',
                'Lainnya'
            ])->default('Umum');
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_berakhir')->nullable();
            $table->foreignId('create_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('ormawa_id')->nullable()->constrained('ormawas')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
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
