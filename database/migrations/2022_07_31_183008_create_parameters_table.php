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
        Schema::create('parameters', function (Blueprint $table) {
            $table->integer('nParCodigo');
            $table->integer('nParClase');
            $table->string('cParJerarquia',500)->default("");
            $table->string('cParNombre',1000)->default("");
            $table->string('cParDescripcion',1000)->default("");
            $table->string('cParValor',255)->default("");
            $table->integer('nParTipo');
            $table->primary(array('nParClase', 'nParCodigo'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parameters');
    }
};
