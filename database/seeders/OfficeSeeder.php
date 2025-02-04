<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gerenciaGeneral = new Office([
            'name' => 'GERENCIA GENERAL',
            'description' => 'GERENCIA GENERAL',
            'logo' => '',
            'code_ue' => '01',
            'goal' => '01',
            'institution_id' => 1,
        ]);
        $gerenciaGeneral->save();

        $oficina = new Office([
            'name' => 'OFICINA DE CONTROL INSTITUCIONAL',
            'description' => 'OFICINA DE CONTROL INSTITUCIONAL',
            'logo' => '',
            'code_ue' => '0101',
            'goal' => '02',
            'father_id' => $gerenciaGeneral->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'OFICINA DE IMAGEN INSTITUCIONAL',
            'description' => 'OFICINA DE IMAGEN INSTITUCIONAL',
            'logo' => '',
            'code_ue' => '0102',
            'goal' => '01',
            'father_id' => $gerenciaGeneral->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'OFICINA DE GESTION POR PROCESOS Y MEJORA CONTINUA',
            'description' => 'OFICINA DE GESTION POR PROCESOS Y MEJORA CONTINUA',
            'logo' => '',
            'code_ue' => '0103',
            'goal' => '01',
            'father_id' => $gerenciaGeneral->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'OFICINA DE ASESORIA LEGAL',
            'description' => 'OFICINA DE ASESORIA LEGAL',
            'logo' => '',
            'code_ue' => '0104',
            'goal' => '01',
            'father_id' => $gerenciaGeneral->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'OFICINA DE PLANEAMIENTO Y PRESUPUESTO',
            'description' => 'OFICINA DE PLANEAMIENTO Y PRESUPUESTO',
            'logo' => '',
            'code_ue' => '0105',
            'goal' => '01',
            'father_id' => $gerenciaGeneral->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $gerenciaAdmi = new Office([
            'name' => 'GERENCIA DE ADMINISTRACION',
            'description' => 'GERENCIA DE ADMINISTRACION',
            'logo' => '',
            'code_ue' => '02',
            'goal' => '01',
            'institution_id' => 1,
        ]);
        $gerenciaAdmi->save();

        $oficina = new Office([
            'name' => 'DEPARTAMENTO DE CONTABILIDAD',
            'description' => 'DEPARTAMENTO DE CONTABILIDAD',
            'logo' => '',
            'code_ue' => '0201',
            'goal' => '01',
            'father_id' => $gerenciaAdmi->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'DEPARTAMENTO DE TESORERIA',
            'description' => 'DEPARTAMENTO DE TESORERIA',
            'logo' => '',
            'code_ue' => '0202',
            'goal' => '01',
            'father_id' => $gerenciaAdmi->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'DEPARTAMENTO DE RECURSOS HUMANOS',
            'description' => 'DEPARTAMENTO DE RECURSOS HUMANOS',
            'logo' => '',
            'code_ue' => '0203',
            'goal' => '01',
            'father_id' => $gerenciaAdmi->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'DEPARTAMENTO DE LOGISTICA Y CONTROL PATRIMONIAL',
            'description' => 'DEPARTAMENTO DE LOGISTICA Y CONTROL PATRIMONIAL',
            'logo' => '',
            'code_ue' => '0204',
            'goal' => '01',
            'father_id' => $gerenciaAdmi->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'DEPARTAMENTO DE INFORMATICA',
            'description' => 'DEPARTAMENTO DE INFORMATICA',
            'logo' => '',
            'code_ue' => '0205',
            'goal' => '01',
            'father_id' => $gerenciaAdmi->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $gerenciaOpe = new Office([
            'name' => 'GERENCIA DE OPERACIONES',
            'description' => 'GERENCIA DE OPERACIONES',
            'logo' => '',
            'code_ue' => '03',
            'goal' => '03',
            'institution_id' => 1,
        ]);
        $gerenciaOpe->save();


        $oficinaDeuda = new Office([
            'name' => 'DEPARTAMENTO DE DETERMINACION DE LA DEUDA',
            'description' => 'DEPARTAMENTO DE DETERMINACION DE LA DEUDA',
            'logo' => '',
            'code_ue' => '0301',
            'goal' => '03',
            'father_id' => $gerenciaOpe->id,
            'institution_id' => 1,
        ]);
        $oficinaDeuda->save();

        $oficina = new Office([
            'name' => 'AREA DE REGISTRO TRIBUTARIO',
            'description' => 'AREA DE REGISTRO TRIBUTARIO',
            'logo' => '',
            'code_ue' => '030101',
            'goal' => '03',
            'father_id' => $oficinaDeuda->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE FISCALIZACION',
            'description' => 'AREA DE FISCALIZACION',
            'logo' => '',
            'code_ue' => '030102',
            'goal' => '03',
            'father_id' => $oficinaDeuda->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE DETERMINACION DE LA DEUDA',
            'description' => 'AREA DE DETERMINACION DE LA DEUDA',
            'logo' => '',
            'code_ue' => '030103',
            'goal' => '03',
            'father_id' => $oficinaDeuda->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficinaCobranza = new Office([
            'name' => 'DEPARTAMENTO DE GESTION DE COBRANZA',
            'description' => 'DEPARTAMENTO DE GESTION DE COBRANZA',
            'logo' => '',
            'code_ue' => '0302',
            'goal' => '03',
            'father_id' => $gerenciaOpe->id,
            'institution_id' => 1,
        ]);
        $oficinaCobranza->save();

        $oficina = new Office([
            'name' => 'AREA DE RECAUDACION DE PRICOS',
            'description' => 'AREA DE RECAUDACION DE PRICOS',
            'logo' => '',
            'code_ue' => '030201',
            'goal' => '03',
            'father_id' => $oficinaCobranza->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE RECAUDACION DE MEPECOS',
            'description' => 'AREA DE RECAUDACION DE MEPECOS',
            'logo' => '',
            'code_ue' => '030202',
            'goal' => '03',
            'father_id' => $oficinaCobranza->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE RECAUDACION DE DEUDAS NO TRIBUTARIAS',
            'description' => 'AREA DE RECAUDACION DE DEUDAS NO TRIBUTARIAS',
            'logo' => '',
            'code_ue' => '030203',
            'goal' => '03',
            'father_id' => $oficinaCobranza->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE RECAUDACION DE MERCADOS',
            'description' => 'AREA DE RECAUDACION DE MERCADOS',
            'logo' => '',
            'code_ue' => '030204',
            'goal' => '03',
            'father_id' => $oficinaCobranza->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE FRACCIONAMIENTO DE LA DEUDA',
            'description' => 'AREA DE FRACCIONAMIENTO DE LA DEUDA',
            'logo' => '',
            'code_ue' => '030205',
            'goal' => '03',
            'father_id' => $oficinaCobranza->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE EJECUCION COACTIVA',
            'description' => 'AREA DE EJECUCION COACTIVA',
            'logo' => '',
            'code_ue' => '030206',
            'goal' => '03',
            'father_id' => $oficinaCobranza->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'DEPARTAMENTO DE RECLAMOS Y DEVOLUCIONES',
            'description' => 'DEPARTAMENTO DE RECLAMOS Y DEVOLUCIONES',
            'logo' => '',
            'code_ue' => '0303',
            'goal' => '03',
            'father_id' => $gerenciaOpe->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'DEPARTAMENTO DE SERVICIOS AL CONTRIBUYENTE',
            'description' => 'DEPARTAMENTO DE SERVICIOS AL CONTRIBUYENTE',
            'logo' => '',
            'code_ue' => '0304',
            'goal' => '03',
            'father_id' => $gerenciaOpe->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE ARCHIVO Y DIGITALIZACION',
            'description' => 'AREA DE ARCHIVO Y DIGITALIZACION',
            'logo' => '',
            'code_ue' => '0305',
            'goal' => '03',
            'father_id' => $gerenciaOpe->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DE EMISIONES Y NOTIFICACIONES',
            'description' => 'AREA DE EMISIONES Y NOTIFICACIONES',
            'logo' => '',
            'code_ue' => '0306',
            'goal' => '03',
            'father_id' => $gerenciaOpe->id,
            'institution_id' => 1,
        ]);
        $oficina->save();

        $oficina = new Office([
            'name' => 'AREA DEL REGISTRO NACIONAL DE SANCIONES',
            'description' => 'AREA DEL REGISTRO NACIONAL DE SANCIONES',
            'logo' => '',
            'code_ue' => '0307',
            'goal' => '03',
            'father_id' => $gerenciaOpe->id,
            'institution_id' => 1,
        ]);
        $oficina->save();
    }
}
