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
        Schema::create('incubadoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hospital');
            $table->foreign('id_hospital')->references('id')->on('hospitals');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_occupied')->default(false);
            $table->unsignedBigInteger('id_estado');
            $table->foreign('id_estado')->references('id')->on('estado_incubadoras');
            $table->string('folio', 100)->unique();
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
        Schema::dropIfExists('incubadoras');
    }
};
