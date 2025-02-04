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
        Schema::table('documents_siaf', function (Blueprint $table) {
            //
            $table->date('detraction_date')->nullable()->after('ha_3');
            $table->string('num_operation', 10)->nullable()->after('detraction_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents_siaf', function (Blueprint $table) {
            //
            $table->dropColumn('detraction_date');
            $table->dropColumn('num_operation');
        });
    }
};
