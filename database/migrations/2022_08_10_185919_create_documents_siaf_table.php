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
        Schema::create('documents_siaf', function (Blueprint $table) {
            $table->id();
            $table->string('month',2)->nullable(false);
            $table->integer('siaf')->nullable(false);
            $table->string('ruc',11)->nullable(false);
            $table->string('type',10)->nullable(false);
            $table->string('type_new',10)->nullable(false);
            $table->string('serie',10)->nullable(false);
            $table->string('number',10)->nullable(false);
            $table->date('date')->nullable(false);
            $table->decimal('amount',10,2)->nullable(false);
            $table->integer('file_upload_id')->nullable();
            $table->string('business_name',150)->nullable();
            $table->decimal('taxable_basis',10,2)->nullable();
            $table->decimal('igv',10,2)->nullable();
            $table->decimal('untaxed_basis',10,2)->nullable();
            $table->decimal('impbp',10,2)->nullable();
            $table->decimal('other_concepts',10,2)->nullable();
            $table->string('doc_code',10)->nullable();
            $table->string('num_doc',10)->nullable();
            $table->string('ha_1',10)->nullable();
            $table->string('ha_2',10)->nullable();
            $table->string('ha_3',10)->nullable();
            $table->date('payment_date')->nullable();
            $table->date('doc_modify_date_of_issue')->nullable();
            $table->string('doc_modify_type',10)->nullable();
            $table->string('doc_modify_serie',10)->nullable();
            $table->string('doc_modify_number',10)->nullable();
            $table->string('last_name',150)->nullable();
            $table->string('mother_last_name',150)->nullable();
            $table->string('name',150)->nullable();
            $table->decimal('total_honorary',10,2)->nullable();
            $table->smallInteger('have_retention')->nullable();
            $table->decimal('retention',10,2)->nullable();
            $table->decimal('net_honorary',10,2)->nullable();
            $table->integer('source')->default(1);
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
        Schema::dropIfExists('documents_siaf');
    }
};
