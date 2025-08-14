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
        Schema::create('ormawas', function (Blueprint $table) {
           $table->id();
            $table->string('nama', 100);
            $table->foreignId('jenis_ormawa_id')->constrained()->onDelete('cascade');
            $table->text('deskripsi');
            $table->string('logo', 255);
            $table->text('visi');
            $table->text('misi');
            $table->enum('status', ['active', 'inactive'])->default('active');
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
