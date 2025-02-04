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
        Schema::create('settlement_classifiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('settlement_id');
            $table->foreign('settlement_id')->references('id')->on('settlements');
            $table->unsignedBigInteger('financial_classifier_id');
            $table->foreign('financial_classifier_id')->references('id')->on('financial_classifiers');
            $table->string('code_classify', 30)->nullable(false);
            $table->string('name_classify')->nullable(false);
            $table->decimal('goal_one',10,2)->nullable();
            $table->decimal('goal_two',10,2)->nullable();
            $table->decimal('goal_three',10,2)->nullable();
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
        Schema::dropIfExists('settlement_classifiers');
    }
};
