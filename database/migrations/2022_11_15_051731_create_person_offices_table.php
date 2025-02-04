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
        Schema::create('person_offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('office_id');
            $table->date('start_date');
            $table->date('end_date')->nullable(true);
            $table->unsignedBigInteger('rol_id')->default(2);
            $table->integer('active')->default(1);
            $table->integer('status')->default(1);
            $table->foreign('person_id')->references('id')->on('people');
            $table->foreign('office_id')->references('id')->on('offices');
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
        Schema::dropIfExists('person_offices');
    }
};
