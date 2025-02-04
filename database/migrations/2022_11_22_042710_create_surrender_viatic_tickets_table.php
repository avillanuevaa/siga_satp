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
        Schema::create('viatic_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->nullable(false);
            $table->string('number')->nullable(false);
            $table->string('memorandum')->nullable(false);
            $table->unsignedBigInteger('person_id')->nullable(false);            
            $table->date('opening_date')->nullable(true);
            $table->decimal('amount',10,2)->nullable(false);
            $table->integer('viatic_type')->nullable(true);
            $table->string('means_of_transport')->nullable(true);       
            $table->date('departure date')->nullable(true);
            $table->date('return_date')->nullable(true);
            $table->integer('number_days')->nullable(true);            
            $table->date('authorization_date')->nullable(true);
            $table->string('authorization_detail')->nullable(true);
            $table->string('destination')->nullable(true);
            $table->string('reason')->nullable(true);
            $table->string('format_number_two')->nullable(true);
            $table->date('file_siaf_date')->nullable(true);
            $table->string('file_siaf_number')->nullable(true);
            $table->date('proof_payment_date')->nullable(true);
            $table->string('proof_payment_number')->nullable(true);           
            $table->string('electronic_payment_order_ate')->nullable(true);                        
            $table->foreign('person_id')->references('id')->on('people');
            $table->tinyInteger('status')->default('1');
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
        Schema::dropIfExists('surrender_viatic_tickets');
    }
};
