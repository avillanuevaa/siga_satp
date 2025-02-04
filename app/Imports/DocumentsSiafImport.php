<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\DocumentSiaf;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DocumentsSiafImport implements ToModel, WithHeadingRow
{
    public $rows = 0;
    private $file_upload_id;

    public function __construct(int $file_upload_id)
    {
        $this->file_upload_id = $file_upload_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        //NO PUEDEN HABER 2 SIAF CON EL MISMO NUMERO DOC
        ++$this->rows;
        $type = $row['tipo_doc'];
        $taxable_basis = 0;
        $igv = 0;
        $untaxed_basis = 0;
        $total_honorary = 0;
        $have_retention = 0;
        $retention = 0;
        $net_honorary = 0;
        $amount = floatval($row['monto_doc']);
        switch ($type) {
            case "01":
            case "14":
                $taxable_basis = floatval($amount) / 1.18;
                $igv = floatval($amount) - floatval($amount) / 1.18;
                break;
            case "07":
                $taxable_basis = -1 * (floatval($amount) / 1.18);
                $igv = -1 * (floatval($amount) - floatval($amount) / 1.18);
                $amount = -1 * $amount;
                break;
            case "02":
                $type = "03";
                $untaxed_basis = $amount;
                break;
            case "27":
                $type = "R";
                $total_honorary = $amount;
                $have_retention = $total_honorary > 1500 ? 1 : 0;
                $retention = $have_retention == 1 ? ($total_honorary * 0.08) * -1 : 0;
                $net_honorary = $total_honorary + $retention;
                $amount = 0;
                break;
        }
        return new DocumentSiaf([
            'file_upload_id'     => $this->file_upload_id,
            'month'     => $row['mes_eje'],
            'siaf'    => $row['expediente'],
            'ruc'    => $row['ruc'],
            'type'    => $row['tipo_doc'],
            'type_new' => $type,
            'serie'    => $row['serie_doc'],
            'number'    => $row['num_doc'],
            'date'    => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha_doc'])),
            'amount'    => $amount,
            'taxable_basis'    => $taxable_basis,
            'igv'    => $igv,
            'untaxed_basis' => $untaxed_basis,
            'total_honorary' => $total_honorary,
            'have_retention' => $have_retention,
            'retention' => $retention,
            'net_honorary' => $net_honorary,
            'status'    => 1,
            'impbp' => 0,
            'other_concepts' => 0
        ]);
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
