<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuatTabelPenghargaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penghargaan', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('uuid');
            $table->string('icon')->nullable();
            $table->string('tahun')->nullable();
            $table->string('label')->nullable();
            $table->string('slug')->nullable();
            $table->string('konten')->nullable();

            $table->integer('id_operator')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penghargaan');
    }
}
