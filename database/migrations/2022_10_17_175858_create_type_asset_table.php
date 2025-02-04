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
        Schema::create('type_assets', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->integer('idcategory');
            $table->string('detail');
            $table->string('classifier');
            $table->string('description') ;       
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
        Schema::dropIfExists('type_assets');
    }
};