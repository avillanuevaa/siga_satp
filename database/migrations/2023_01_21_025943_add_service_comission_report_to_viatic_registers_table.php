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
        Schema::table('viatic_registers', function (Blueprint $table) {
            //
            $table->string('service_commission_a')->nullable(true);
            $table->string('service_commission_from')->nullable(true);
            $table->string('service_commission_date')->nullable(true);
            $table->string('service_commission_activities_performed')->nullable(true);
            $table->string('service_commission_results_obtained')->nullable(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('viatic_registers', function (Blueprint $table) {
            //
            $table->dropColumn('service_commission_a');
            $table->dropColumn('service_commission_from');
            $table->dropColumn('service_commission_date');
            $table->dropColumn('service_commission_activities_performed');
            $table->dropColumn('service_commission_results_obtained');

        });
    }
};
