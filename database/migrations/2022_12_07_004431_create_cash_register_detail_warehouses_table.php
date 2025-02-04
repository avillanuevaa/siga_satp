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
        Schema::create('cash_register_detail_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('package')->nullable();
            $table->string('detail')->nullable();
            $table->string('measure')->nullable();
            $table->decimal('quantity',10,2)->nullable();
            $table->decimal('unit_value',10,2)->nullable();
            $table->decimal('total',10,2)->nullable();
            $table->tinyInteger('lesser_package')->nullable();
            $table->unsignedBigInteger('cash_register_detail_id');
            $table->foreign('cash_register_detail_id')->references('id')->on('cash_register_details');
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
        Schema::dropIfExists('cash_register_detail_warehouses');
    }
};
