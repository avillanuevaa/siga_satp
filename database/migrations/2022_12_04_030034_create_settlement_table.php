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
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->default(1);
            $table->integer('number_correlative')->nullable(false);
            $table->integer('request_type')->nullable(false); // Psajes o viaticos
            $table->string('year')->nullable(false);
            $table->string('approved_amount')->nullable(false); 
            $table->string('budget_certificate')->nullable(false);
            $table->string('reason')->nullable(false);
            $table->unsignedBigInteger('person_id')->default(1);
            $table->date('authorization_date')->nullable(false);
            $table->string('authorization_detail')->nullable(false);
            $table->integer('viatic_type')->nullable(true);
            $table->string('destination')->nullable(true);
            $table->integer('means_of_transport')->nullable(true);
            $table->integer('number_days')->nullable(true);
            $table->date('departure_date')->nullable(true);
            $table->date('return_date')->nullable(true);
            $table->string('format_number_two')->nullable(true);
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
        Schema::dropIfExists('settlement');
    }
};
