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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->integer('request_type')->nullable(false);
            $table->integer('number_correlative')->nullable(false);
            $table->date('request_date')->nullable(false);
            $table->string('year')->nullable(false);
            $table->string('request_amount')->nullable(false);
            $table->unsignedBigInteger('person_id')->default(1);
            $table->string('reference_document')->nullable(true);
            $table->string('purpose')->nullable(true);
            $table->string('justification')->nullable(true);
            $table->integer('viatic_type')->nullable(true);
            $table->string('destination')->nullable(true);
            $table->integer('means_of_transport')->nullable(true);
            $table->integer('number_days')->nullable(true);
            $table->date('departure_date')->nullable(true);
            $table->date('return_date')->nullable(true);
            $table->integer('approval')->default(0);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('requests');
    }
};
