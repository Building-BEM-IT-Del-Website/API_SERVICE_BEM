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
          Schema::create('struktur_organisasis', function (Blueprint $table) {
              $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ormawa_id')->constrained('ormawas')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatan')->onDelete('cascade');
            $table->string('periode', 20);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable(); // wajib
            $table->enum('status', ['active', 'nonactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['user_id', 'ormawa_id']); // agar user tidak bisa double dalam satu ormawa
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
