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
            $table->string('affidavit_description_lost_documents')->nullable(true);
            $table->string('affidavit_amount_lost_documents')->nullable(true);
            $table->string('affidavit_amount_undocumented_expenses')->nullable(true);
            //

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
            $table->dropColumn('affidavit_description_lost_documents');
            $table->dropColumn('affidavit_amount_lost_documents');
            $table->dropColumn('affidavit_amount_undocumented_expenses');
        });
    }
};
