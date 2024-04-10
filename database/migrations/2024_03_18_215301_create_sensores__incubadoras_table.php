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
        Schema::create('sensores__incubadoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_incubadora');
            $table->foreign('id_incubadora')->references('id')->on('incubadoras');
            $table->unsignedBigInteger('id_sensor');
            $table->foreign('id_sensor')->references('id')->on('sensores');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('sensores__incubadoras');
    }
};
