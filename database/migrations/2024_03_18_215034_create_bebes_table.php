<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bebes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('sexo', 1);
            $table->date('fecha_nacimiento');
            $table->integer('edad');
            $table->float('peso');
            $table->unsignedBigInteger('id_estado')->default(1);
            $table->foreign('id_estado')->references('id')->on('estado_del_bebes');
            $table->unsignedBigInteger('id_incubadora');
            $table->foreign('id_incubadora')->references('id')->on('incubadoras');
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
        Schema::dropIfExists('bebes');
    }
};
