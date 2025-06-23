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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('verDashboard')->default(1);
            $table->integer('verMantenimientoClasificadores')->default(1);
            $table->integer('verMantenimientoTrabajadores')->default(1);
            $table->integer('verMantenimientoOficinas')->default(1);
            $table->integer('verContabilidadSiaf')->default(1);
            $table->integer('verContabilidadExportacion')->default(1);
            $table->integer('verRendicionesSolicitudes')->default(1);
            $table->integer('verRendicionesLiquidaciones')->default(1);
            $table->integer('verRendicionesCajaChica')->default(1);
            $table->integer('verRendicionesEncargos')->default(1);
            $table->integer('verRendicionesViaticos')->default(1);
            $table->integer('verSeguridad')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'verDashboard',
                'verMantenimientoClasificadores',
                'verMantenimientoTrabajadores',
                'verMantenimientoOficinas',
                'verContabilidadSiaf',
                'verContabilidadExportacion',
                'verRendicionesSolicitudes',
                'verRendicionesLiquidaciones',
                'verRendicionesCajaChica',
                'verRendicionesEncargos',
                'verRendicionesViaticos',
                'verSeguridad'
            ]);
        });
    }
};
