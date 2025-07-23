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
            $table->string('nama_pengumuman')->unique();
            $table->text('deskripsi')->nullable();
            $table->foreignId('kategoris_id')->nullable()->constrained('kategoris')->onDelete('cascade');
            $table->foreignId('create_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->datetime('dibuat');
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
