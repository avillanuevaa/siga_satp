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
        Schema::create('viatic_tickets_details', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->nullable(false);
            $table->string('number')->nullable(false);
            $table->integer('voucher_type')->nullable(false);
            $table->string('voucher_serie')->nullable(false);
            $table->string('voucher_number')->nullable(false);
            $table->string('supplier_document_type')->nullable(false);
            $table->string('supplier_numer')->nullable(false);
            $table->string('supplier_name')->nullable(false);
            $table->decimal('igv',2,2)->nullable(false);
            $table->decimal('untaxed_base',4,2)->nullable(true);
            $table->decimal('impbp',2,2)->nullable(true);            
            $table->decimal('other_concepts',4,2)->nullable(true);
            $table->string('cost_center_id')->nullable(true);            
            $table->string('goal_id')->nullable(true);
            $table->string('budget_item_classifier')->nullable(true);
            $table->string('budget_item_amount')->nullable(true);            
            $table->unsignedBigInteger('viatic_ticket_id');
            $table->foreign('viatic_ticket_id')->references('id')->on('viatic_tickets');
            
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
        Schema::dropIfExists('viatic_tickets_details');
    }
};
