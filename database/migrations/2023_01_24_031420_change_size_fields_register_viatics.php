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
        //
        Schema::table('viatic_registers', function (Blueprint $table) {
            $table->string('affidavit_description_lost_documents', 500)->change();
            $table->string('affidavit_amount_lost_documents', 500)->change();
            $table->string('affidavit_amount_undocumented_expenses', 500)->change();
        });

        Schema::table('viatic_registers', function (Blueprint $table) {
            $table->string('service_commission_a', 500)->change();
            $table->string('service_commission_from', 500)->change();
            $table->string('service_commission_date', 500)->change();
            $table->string('service_commission_activities_performed', 500)->change();
            $table->string('service_commission_results_obtained', 500)->change();
        });
    }


    





    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
