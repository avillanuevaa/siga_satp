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
        Schema::create('viatic_register_details', function (Blueprint $table) {
            $table->id();
            $table->date('issue_date')->nullable(true);
            $table->string('issue_type')->nullable(false);
            $table->string('issue_description')->nullable(false);
            $table->string('issue_serie')->nullable(false);
            $table->string('issue_number')->nullable(false);
            $table->integer('supplier_type')->nullable(false);
            $table->string('supplier_number')->nullable(false);
            $table->string('supplier_name')->nullable(false);
            $table->decimal('taxed_base',10,2)->nullable(false);
            $table->decimal('igv',10,2)->nullable(false);
            $table->decimal('untaxed_base',10,2)->nullable(true);
            $table->decimal('impbp',10,2)->nullable(true);            
            $table->decimal('other_concepts',10,2)->nullable(true);
            $table->decimal('total',10,2)->nullable(false);
            $table->string('cost_center_code')->nullable(false);
            $table->string('cost_center_description')->nullable(false);
            $table->string('goal_code')->nullable(false);
            $table->string('goal_description')->nullable(false);
            $table->string('classifier_code')->nullable(false);
            $table->string('classifier_descripcion')->nullable(false);
            $table->decimal('classifier_amount',10,2)->nullable(false);
            $table->string('expense_description',100)->nullable(true);
            $table->unsignedBigInteger('viatic_register_id');
            $table->foreign('viatic_register_id')->references('id')->on('viatic_registers');
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
        Schema::dropIfExists('viatic_register_details');
    }
};
