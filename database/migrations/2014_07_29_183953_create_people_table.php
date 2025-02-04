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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name',150)->nullable(false);
            $table->string('lastname',150)->nullable(false);
            $table->unsignedBigInteger('person_type_id');
            $table->unsignedBigInteger('document_type_id');
            $table->string('document_number',20)->nullable(false);
            $table->string('address',150)->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('cellphone', 11)->nullable();
            $table->string('image')->nullable();
            $table->integer('active')->default(1);
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
        Schema::dropIfExists('people');
    }
};
