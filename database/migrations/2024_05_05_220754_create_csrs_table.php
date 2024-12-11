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
        Schema::create('csrs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_pic')->nullable();
            $table->string('no_pic')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('email')->nullable();
            $table->decimal('donasi')->nullable();                        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csrs');
    }
};
