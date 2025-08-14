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
            Schema::create('jabatan', function (Blueprint $table) {
            $table->id(); // id INT(11) PK
            $table->string('nama', 100); // VARCHAR(100)
            $table->text('deskripsi'); // TEXT
            $table->integer('level'); // INT(11)
            $table->softDeletes(); // deleted_at
            $table->timestamps(); // created_at dan updated_at
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
