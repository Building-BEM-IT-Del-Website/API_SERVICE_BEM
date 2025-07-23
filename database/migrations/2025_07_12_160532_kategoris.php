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
         Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_kategoris_id')->nullable()->constrained('sub_kategoris')->onDelete('cascade');
            $table->string('nama')->unique();
            $table->string('deskripsi')->nullable();
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
