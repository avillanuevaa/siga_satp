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
        Schema::create('viatic_registers', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->nullable(false);
            $table->string('number')->nullable(false);
            $table->date('opening_date')->nullable(true);
            $table->date('closing_date')->nullable(true);
            $table->unsignedBigInteger('settlement_id');
            $table->foreign('settlement_id')->references('id')->on('settlements');
            $table->date('siaf_date')->nullable(true);
            $table->string('siaf_number')->nullable(false);
            $table->date('voucher_date')->nullable(true);
            $table->string('voucher_number')->nullable(false);
            $table->date('order_pay_electronic_date')->nullable(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('amount_to_pay', 10, 2)->nullable(true);
            $table->decimal('amount_to_returned', 10, 2)->nullable(true);
            $table->string('surrender_report')->nullable(true);
            $table->tinyInteger('closed')->default(0)->nullable(true);
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
        Schema::dropIfExists('viatic_registers');
    }
};
