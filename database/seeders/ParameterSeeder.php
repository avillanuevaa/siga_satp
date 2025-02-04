<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parameter;

class ParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parameter = [
            ['nParCodigo' => 0, 'nParClase' => '1000', 'cParJerarquia' => '01', 'cParNombre' => '..:: Tipo de Registros ::..', 'cParDescripcion' => '..:: Tipo de Registros ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1000', 'cParJerarquia' => '0101', 'cParNombre' => 'ASIENTO DE APERTURA', 'cParDescripcion' => 'AP', 'cParValor' => '00', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1000', 'cParJerarquia' => '0102', 'cParNombre' => 'PRESUPUESTO INSTITUCIONAL DE APERTURA', 'cParDescripcion' => 'PIA', 'cParValor' => '01', 'nParTipo' => 1],
            ['nParCodigo' => 3, 'nParClase' => '1000', 'cParJerarquia' => '0103', 'cParNombre' => 'MODIFICACIÓN PRESUPUESTARIA', 'cParDescripcion' => 'MP', 'cParValor' => '02', 'nParTipo' => 1],
            ['nParCodigo' => 4, 'nParClase' => '1000', 'cParJerarquia' => '0104', 'cParNombre' => 'EJECUCIÓN PRESUPUESTAL DEL GASTO', 'cParDescripcion' => 'EPG', 'cParValor' => '03', 'nParTipo' => 1],
            ['nParCodigo' => 5, 'nParClase' => '1000', 'cParJerarquia' => '0105', 'cParNombre' => 'EJECUCIÓN PRESUPUESTAL DEL INGRESO', 'cParDescripcion' => 'EPI', 'cParValor' => '04', 'nParTipo' => 1],
            ['nParCodigo' => 6, 'nParClase' => '1000', 'cParJerarquia' => '0106', 'cParNombre' => 'ENTRADA EN CUENTA RECAUDADORA Y PROSEGUR', 'cParDescripcion' => 'ECRP', 'cParValor' => '05', 'nParTipo' => 1],
            ['nParCodigo' => 7, 'nParClase' => '1000', 'cParJerarquia' => '0107', 'cParNombre' => 'TRANSFERENCIA POR RECAUDACIÓN', 'cParDescripcion' => 'TR', 'cParValor' => '06', 'nParTipo' => 1],
            ['nParCodigo' => 9, 'nParClase' => '1000', 'cParJerarquia' => '0108', 'cParNombre' => 'ENTRADA POR MEDIOS MAGNETICOS', 'cParDescripcion' => 'EMM', 'cParValor' => '07', 'nParTipo' => 1],
            ['nParCodigo' => 10, 'nParClase' => '1000', 'cParJerarquia' => '0109', 'cParNombre' => 'TRASNFERENCIA DE RECAUDACIÓN POR MEDIOS MAGNETICOS', 'cParDescripcion' => 'TRMM', 'cParValor' => '08', 'nParTipo' => 1],
            ['nParCodigo' => 11, 'nParClase' => '1000', 'cParJerarquia' => '0110', 'cParNombre' => 'TRANSFERENCIA ENTRE CUENTAS', 'cParDescripcion' => 'TEC', 'cParValor' => '09', 'nParTipo' => 1],
            ['nParCodigo' => 12, 'nParClase' => '1000', 'cParJerarquia' => '0111', 'cParNombre' => 'SALIDA DE CUENTA BANCARIA', 'cParDescripcion' => 'ACB', 'cParValor' => '10', 'nParTipo' => 1],
            ['nParCodigo' => 13, 'nParClase' => '1000', 'cParJerarquia' => '0112', 'cParNombre' => 'DEPOSITO EN CUENTA BANCARIA', 'cParDescripcion' => 'DCB', 'cParValor' => '11', 'nParTipo' => 1],
            ['nParCodigo' => 14, 'nParClase' => '1000', 'cParJerarquia' => '0113', 'cParNombre' => 'NOTA DE ANULACIÓN DE GIROS', 'cParDescripcion' => 'NAG', 'cParValor' => '12', 'nParTipo' => 1],
            ['nParCodigo' => 15, 'nParClase' => '1000', 'cParJerarquia' => '0114', 'cParNombre' => 'RECONOCIMIENTO DE INGRESOS', 'cParDescripcion' => 'RI', 'cParValor' => '13', 'nParTipo' => 1],
            ['nParCodigo' => 16, 'nParClase' => '1000', 'cParJerarquia' => '0115', 'cParNombre' => 'COMPROBANTE DE PAGO', 'cParDescripcion' => 'CP', 'cParValor' => '14', 'nParTipo' => 1],
            ['nParCodigo' => 17, 'nParClase' => '1000', 'cParJerarquia' => '0116', 'cParNombre' => 'NOTAS DE CONTABILIDAD', 'cParDescripcion' => 'NC', 'cParValor' => '15', 'nParTipo' => 1],
            ['nParCodigo' => 18, 'nParClase' => '1000', 'cParJerarquia' => '0117', 'cParNombre' => 'NOTA DE CIERRE ANUAL', 'cParDescripcion' => 'NCA', 'cParValor' => '16', 'nParTipo' => 1],
            ['nParCodigo' => 19, 'nParClase' => '1000', 'cParJerarquia' => '0118', 'cParNombre' => 'AJUSTES EN LA EJECUCIÓN PRESUPUESTARIA', 'cParDescripcion' => 'AEP', 'cParValor' => '17', 'nParTipo' => 1],
            
            
            ['nParCodigo' => 0, 'nParClase' => '1001', 'cParJerarquia' => '01', 'cParNombre' => '..:: Tipo de clasificadores de ingresos y gastos ::..', 'cParDescripcion' => '..:: Tipo de clasificadores de ingresos y gastos ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1001', 'cParJerarquia' => '0101', 'cParNombre' => 'GASTOS', 'cParDescripcion' => 'GASTOS', 'cParValor' => '1', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1001', 'cParJerarquia' => '0102', 'cParNombre' => 'INGRESOS', 'cParDescripcion' => 'INGRESOS', 'cParValor' => '2', 'nParTipo' => 1],


            ['nParCodigo' => 0, 'nParClase' => '1002', 'cParJerarquia' => '01', 'cParNombre' => '..:: Meta presupuestaria ::..', 'cParDescripcion' => '..:: Meta presupuestaria ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1002', 'cParJerarquia' => '0101', 'cParNombre' => 'GESTIÓN ADMINISTRATIVA', 'cParDescripcion' => 'GA', 'cParValor' => '01', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1002', 'cParJerarquia' => '0102', 'cParNombre' => 'CONTROL INTERNO', 'cParDescripcion' => 'CI', 'cParValor' => '02', 'nParTipo' => 1],
            ['nParCodigo' => 3, 'nParClase' => '1002', 'cParJerarquia' => '0103', 'cParNombre' => 'GESTIÓN OPERATIVA', 'cParDescripcion' => 'GO', 'cParValor' => '03', 'nParTipo' => 1],


            ['nParCodigo' => 0, 'nParClase' => '1003', 'cParJerarquia' => '01', 'cParNombre' => '..:: Tipo de contratacion ::..', 'cParDescripcion' => '..:: Tipo de contratacion ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1003', 'cParJerarquia' => '0101', 'cParNombre' => 'DECRETO LEGISLATIVO 728', 'cParDescripcion' => 'DECRETO LEGISLATIVO 728', 'cParValor' => '1', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1003', 'cParJerarquia' => '0102', 'cParNombre' => 'DECRETO LEGISLATIVO 1057', 'cParDescripcion' => 'DECRETO LEGISLATIVO 1057', 'cParValor' => '2', 'nParTipo' => 1],
            

            ['nParCodigo' => 0, 'nParClase' => '1004', 'cParJerarquia' => '01', 'cParNombre' => '..:: Tipo de documento de identidad ::..', 'cParDescripcion' => '..:: Tipo de documento de identidad ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1004', 'cParJerarquia' => '0101', 'cParNombre' => 'OTROS TIPOS DE DOCUMENTOS', 'cParDescripcion' => 'OTROS TIPOS DE DOCUMENTOS', 'cParValor' => '0', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1004', 'cParJerarquia' => '0102', 'cParNombre' => 'DOCUMENTO NACIONAL DE IDENTIDAD (DNI)', 'cParDescripcion' => 'DOCUMENTO NACIONAL DE IDENTIDAD (DNI)', 'cParValor' => '1', 'nParTipo' => 1],
            ['nParCodigo' => 3, 'nParClase' => '1004', 'cParJerarquia' => '0103', 'cParNombre' => 'CARNET DE EXTRANJERIA', 'cParDescripcion' => 'CARNET DE EXTRANJERIA', 'cParValor' => '4', 'nParTipo' => 1],
            ['nParCodigo' => 4, 'nParClase' => '1004', 'cParJerarquia' => '0104', 'cParNombre' => 'REGISTRO ÚNICO DE CONTRIBUYENTES', 'cParDescripcion' => 'REGISTRO ÚNICO DE CONTRIBUYENTES', 'cParValor' => '6', 'nParTipo' => 1],
            ['nParCodigo' => 5, 'nParClase' => '1004', 'cParJerarquia' => '0105', 'cParNombre' => 'PASAPORTE', 'cParDescripcion' => 'PASAPORTE', 'cParValor' => '7', 'nParTipo' => 1],


            ['nParCodigo' => 0, 'nParClase' => '1005', 'cParJerarquia' => '01', 'cParNombre' => '..:: Codigo de la unidad de medida ::..', 'cParDescripcion' => '..:: Codigo de la unidad de medida ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1005', 'cParJerarquia' => '0101', 'cParNombre' => 'UNIDAD', 'cParDescripcion' => 'UNIDAD', 'cParValor' => '01', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1005', 'cParJerarquia' => '0102', 'cParNombre' => 'PAQUETE', 'cParDescripcion' => 'PAQUETE', 'cParValor' => '02', 'nParTipo' => 1],
            ['nParCodigo' => 3, 'nParClase' => '1005', 'cParJerarquia' => '0103', 'cParNombre' => 'CAJA', 'cParDescripcion' => 'CAJA', 'cParValor' => '03', 'nParTipo' => 1],
            ['nParCodigo' => 4, 'nParClase' => '1005', 'cParJerarquia' => '0104', 'cParNombre' => 'LITRO', 'cParDescripcion' => 'LITRO', 'cParValor' => '04', 'nParTipo' => 1],
            ['nParCodigo' => 5, 'nParClase' => '1005', 'cParJerarquia' => '0105', 'cParNombre' => 'MILLAR', 'cParDescripcion' => 'MILLAR', 'cParValor' => '05', 'nParTipo' => 1],
            ['nParCodigo' => 6, 'nParClase' => '1005', 'cParJerarquia' => '0106', 'cParNombre' => 'GALON', 'cParDescripcion' => 'GALON', 'cParValor' => '06', 'nParTipo' => 1],
            

            ['nParCodigo' => 0, 'nParClase' => '1006', 'cParJerarquia' => '01', 'cParNombre' => '..:: Tipo de comprobante de pago o documento ::..', 'cParDescripcion' => '..:: Tipo de comprobante de pago o documento ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1006', 'cParJerarquia' => '0101', 'cParNombre' => 'OTROS (ESPECIFICAR)', 'cParDescripcion' => 'OTROS (ESPECIFICAR)', 'cParValor' => '00', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1006', 'cParJerarquia' => '0102', 'cParNombre' => 'FACTURA', 'cParDescripcion' => 'FACTURA', 'cParValor' => '01', 'nParTipo' => 1],
            ['nParCodigo' => 3, 'nParClase' => '1006', 'cParJerarquia' => '0103', 'cParNombre' => 'RECIBO DE INGRESOS', 'cParDescripcion' => 'RECIBO DE INGRESOS', 'cParValor' => '40', 'nParTipo' => 1],
            ['nParCodigo' => 4, 'nParClase' => '1006', 'cParJerarquia' => '0104', 'cParNombre' => 'BOLETA DE VENTA', 'cParDescripcion' => 'BOLETA DE VENTA', 'cParValor' => '03', 'nParTipo' => 1],
            ['nParCodigo' => 5, 'nParClase' => '1006', 'cParJerarquia' => '0105', 'cParNombre' => 'BOLETO DE COMPAÑÍA DE AVIACIÓN COMERCIAL POR EL SERVICIO DE TRANSPORTE AÉREO DE PASAJEROS', 'cParDescripcion' => 'BOLETO DE COMPAÑÍA DE AVIACIÓN COMERCIAL POR EL SERVICIO DE TRANSPORTE AÉREO DE PASAJEROS', 'cParValor' => '05', 'nParTipo' => 1],
            ['nParCodigo' => 6, 'nParClase' => '1006', 'cParJerarquia' => '0106', 'cParNombre' => 'NOTA DE CRÉDITO', 'cParDescripcion' => 'NOTA DE CRÉDITO', 'cParValor' => '07', 'nParTipo' => 1],
            ['nParCodigo' => 7, 'nParClase' => '1006', 'cParJerarquia' => '0107', 'cParNombre' => 'NOTA DE DEBITO', 'cParDescripcion' => 'NOTA DE DEBITO', 'cParValor' => '08', 'nParTipo' => 1],
            ['nParCodigo' => 8, 'nParClase' => '1006', 'cParJerarquia' => '0108', 'cParNombre' => 'GUÍA DE REMISIÓN - REMITENTE', 'cParDescripcion' => 'GUÍA DE REMISIÓN - REMITENTE', 'cParValor' => '09', 'nParTipo' => 1],
            ['nParCodigo' => 9, 'nParClase' => '1006', 'cParJerarquia' => '0109', 'cParNombre' => 'RECIBO POR ARRENDAMIENTO', 'cParDescripcion' => 'RECIBO POR ARRENDAMIENTO', 'cParValor' => '10', 'nParTipo' => 1],
            ['nParCodigo' => 10, 'nParClase' => '1006', 'cParJerarquia' => '0110', 'cParNombre' => 'RECIBO POR SERVICIOS PÚBLICOS', 'cParDescripcion' => 'RECIBO POR SERVICIOS PÚBLICOS DE SUMINISTRO DE ENERGÍA ELÉCTRICA, AGUA, TELÉFONO, TELEX Y TELEGRÁFICOS Y OTROS SERVICIOS COMPLEMENTARIOS QUE SE INCLUYAN EN EL RECIBO DE SERVICIO PÚBLICO', 'cParValor' => '14', 'nParTipo' => 1],
            ['nParCodigo' => 11, 'nParClase' => '1006', 'cParJerarquia' => '0111', 'cParNombre' => 'BOLETO EMITIDO POR LAS EMPRESAS DE TRANSPORTE PÚBLICO URBANO DE PASAJEROS', 'cParDescripcion' => 'BOLETO EMITIDO POR LAS EMPRESAS DE TRANSPORTE PÚBLICO URBANO DE PASAJEROS', 'cParValor' => '15', 'nParTipo' => 1],
            ['nParCodigo' => 12, 'nParClase' => '1006', 'cParJerarquia' => '0112', 'cParNombre' => 'BOLETO DE VIAJE EMITIDO POR LAS EMPRESAS DE TRANSPORTE PÚBLICO INTERPROVINCIAL DE PASAJEROS DENTRO DEL PAÍS', 'cParDescripcion' => 'BOLETO DE VIAJE EMITIDO POR LAS EMPRESAS DE TRANSPORTE PÚBLICO INTERPROVINCIAL DE PASAJEROS DENTRO DEL PAÍS', 'cParValor' => '16', 'nParTipo' => 1],
            ['nParCodigo' => 13, 'nParClase' => '1006', 'cParJerarquia' => '0113', 'cParNombre' => 'VOUCHER DE PAGO', 'cParDescripcion' => 'VOUCHER DE PAGO', 'cParValor' => '39', 'nParTipo' => 1],
            ['nParCodigo' => 14, 'nParClase' => '1006', 'cParJerarquia' => '0114', 'cParNombre' => 'RECIBO DEFINITIVO DE CAJA CHICA', 'cParDescripcion' => 'RECIBO DEFINITIVO DE CAJA CHICA', 'cParValor' => '89', 'nParTipo' => 1],
            ['nParCodigo' => 15, 'nParClase' => '1006', 'cParJerarquia' => '0115', 'cParNombre' => 'RECIBO POR HONORARIOS', 'cParDescripcion' => 'RECIBO POR HONORARIOS', 'cParValor' => 'R', 'nParTipo' => 1],
            ['nParCodigo' => 16, 'nParClase' => '1006', 'cParJerarquia' => '0116', 'cParNombre' => 'DOCUMENTOS AUTORIZADOS POR SUNAT', 'cParDescripcion' => 'DOCUMENTOS AUTORIZADOS POR SUNAT', 'cParValor' => '30', 'nParTipo' => 1],
            ['nParCodigo' => 17, 'nParClase' => '1006', 'cParJerarquia' => '0117', 'cParNombre' => 'DOCUMENTOS AUTORIZADOS POR SUNAT', 'cParDescripcion' => 'DOCUMENTOS AUTORIZADOS POR SUNAT', 'cParValor' => '42', 'nParTipo' => 1],
            

            ['nParCodigo' => 0, 'nParClase' => '1007', 'cParJerarquia' => '01', 'cParNombre' => '..:: Tipo de almacen ::..', 'cParDescripcion' => '..:: Tipo de almacen ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1007', 'cParJerarquia' => '0101', 'cParNombre' => 'PAPELERIA EN GENERAL, UTILES Y MATERIALES DE OFICINA', 'cParDescripcion' => 'PAPELERIA EN GENERAL, UTILES Y MATERIALES DE OFICINA', 'cParValor' => '01', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1007', 'cParJerarquia' => '0102', 'cParNombre' => 'REPUESTOS Y ACCESORIOS', 'cParDescripcion' => 'REPUESTOS Y ACCESORIOS', 'cParValor' => '02', 'nParTipo' => 1],
            ['nParCodigo' => 3, 'nParClase' => '1007', 'cParJerarquia' => '0103', 'cParNombre' => 'ASEO, LIMPIEZA Y TOCADOR', 'cParDescripcion' => 'ASEO, LIMPIEZA Y TOCADOR', 'cParValor' => '03', 'nParTipo' => 1],
            ['nParCodigo' => 4, 'nParClase' => '1007', 'cParJerarquia' => '0104', 'cParNombre' => 'VESTUARIOS Y TEXTILES', 'cParDescripcion' => 'VESTUARIOS Y TEXTILES', 'cParValor' => '04', 'nParTipo' => 1],
            ['nParCodigo' => 5, 'nParClase' => '1007', 'cParJerarquia' => '0105', 'cParNombre' => 'ELECTRICIDAD, ILUMINACIÓN Y ELECTRÓNICA', 'cParDescripcion' => 'ELECTRICIDAD, ILUMINACIÓN Y ELECTRÓNICA', 'cParValor' => '05', 'nParTipo' => 1],
            ['nParCodigo' => 6, 'nParClase' => '1007', 'cParJerarquia' => '0106', 'cParNombre' => 'SUMINISTROS MÉDICOS', 'cParDescripcion' => 'SUMINISTROS MÉDICOS', 'cParValor' => '06', 'nParTipo' => 1],
            ['nParCodigo' => 7, 'nParClase' => '1007', 'cParJerarquia' => '0107', 'cParNombre' => 'LIBROS, TEXTOS Y OTROS MATERIALES IMPRESOS', 'cParDescripcion' => 'LIBROS, TEXTOS Y OTROS MATERIALES IMPRESOS', 'cParValor' => '07', 'nParTipo' => 1],
            ['nParCodigo' => 8, 'nParClase' => '1007', 'cParJerarquia' => '0108', 'cParNombre' => 'SUMINISTROS PARA MANTENIMIENTO Y REPARACIÓN', 'cParDescripcion' => 'SUMINISTROS PARA MANTENIMIENTO Y REPARACIÓN', 'cParValor' => '08', 'nParTipo' => 1],
            ['nParCodigo' => 9, 'nParClase' => '1007', 'cParJerarquia' => '0109', 'cParNombre' => 'OTROS SUMINISTROS', 'cParDescripcion' => 'OTROS SUMINISTROS', 'cParValor' => '09', 'nParTipo' => 1],
            ['nParCodigo' => 10, 'nParClase' => '1007', 'cParJerarquia' => '0110', 'cParNombre' => 'ACTIVO FIJO', 'cParDescripcion' => 'ACTIVO FIJO', 'cParValor' => '10', 'nParTipo' => 1],
            ['nParCodigo' => 11, 'nParClase' => '1007', 'cParJerarquia' => '0111', 'cParNombre' => 'BOLETO EMITIDO POR LAS EMPRESAS DE TRANSPORTE PÚBLICO URBANO DE PASAJEROS', 'cParDescripcion' => 'BOLETO EMITIDO POR LAS EMPRESAS DE TRANSPORTE PÚBLICO URBANO DE PASAJEROS', 'cParValor' => '11', 'nParTipo' => 1],
            ['nParCodigo' => 12, 'nParClase' => '1007', 'cParJerarquia' => '0112', 'cParNombre' => 'BIENES MENORES', 'cParDescripcion' => 'BIENES MENORES', 'cParValor' => '12', 'nParTipo' => 1],
            ['nParCodigo' => 13, 'nParClase' => '1007', 'cParJerarquia' => '0113', 'cParNombre' => 'INTANGIBLES', 'cParDescripcion' => 'INTANGIBLES', 'cParValor' => '13', 'nParTipo' => 1],
            ['nParCodigo' => 14, 'nParClase' => '1007', 'cParJerarquia' => '0114', 'cParNombre' => 'BIENES DE ENTREGAS A RENDIR', 'cParDescripcion' => 'BIENES DE ENTREGAS A RENDIR', 'cParValor' => '14', 'nParTipo' => 1],
           

            ['nParCodigo' => 0, 'nParClase' => '1008', 'cParJerarquia' => '01', 'cParNombre' => '..:: Estados PLE ::..', 'cParDescripcion' => '..:: Estados PLE ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1008', 'cParJerarquia' => '0101', 'cParNombre' => 'COMPRAS SIN IGV', 'cParDescripcion' => 'COMPRAS SIN IGV', 'cParValor' => '0', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1008', 'cParJerarquia' => '0102', 'cParNombre' => 'COMPRAS CON IGV DEL MES ACTUAL', 'cParDescripcion' => 'COMPRAS CON IGV DEL MES ACTUAL', 'cParValor' => '1', 'nParTipo' => 1],
            ['nParCodigo' => 3, 'nParClase' => '1008', 'cParJerarquia' => '0101', 'cParNombre' => 'COMPRAS CON IGV DE MESES ANTERIORES', 'cParDescripcion' => 'COMPRAS CON IGV DE MESES ANTERIORES', 'cParValor' => '6', 'nParTipo' => 1],
            ['nParCodigo' => 4, 'nParClase' => '1008', 'cParJerarquia' => '0102', 'cParNombre' => 'COMPRAS CON IGV DE MESES ANTERIORES A LOS 12 MESES ', 'cParDescripcion' => 'COMPRAS CON IGV DE MESES ANTERIORES A LOS 12 MESES ', 'cParValor' => '7', 'nParTipo' => 1],
            ['nParCodigo' => 5, 'nParClase' => '1008', 'cParJerarquia' => '0101', 'cParNombre' => 'COMPRAS QUE MODIFICAN CP MES ANT.', 'cParDescripcion' => 'COMPRAS QUE MODIFICAN CP MES ANT.', 'cParValor' => '9', 'nParTipo' => 1],
            

            ['nParCodigo' => 0, 'nParClase' => '1009', 'cParJerarquia' => '01', 'cParNombre' => '..::  PDT IGV/RENTA 	 ::..', 'cParDescripcion' => '..::  PDT IGV/RENTA 	 ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1009', 'cParJerarquia' => '0101', 'cParNombre' => 'COMPRAS NETAS DESTINADAS A VENTAS NO GRAVADAS - NACIONALES', 'cParDescripcion' => 'COMPRAS NETAS DESTINADAS A VENTAS NO GRAVADAS - NACIONALES', 'cParValor' => '113', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1009', 'cParJerarquia' => '0102', 'cParNombre' => 'COMPRAS INTERNAS NO GRAVADAS', 'cParDescripcion' => 'COMPRAS INTERNAS NO GRAVADAS', 'cParValor' => '120', 'nParTipo' => 1],
              

            ['nParCodigo' => 0, 'nParClase' => '1010', 'cParJerarquia' => '01', 'cParNombre' => '..::  Tipo de liquidaciones  ::..', 'cParDescripcion' => '..::  Tipo de liquidaciones 	 ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1010', 'cParJerarquia' => '0101', 'cParNombre' => 'ENCARGO', 'cParDescripcion' => 'ENCARGO', 'cParValor' => '1', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1010', 'cParJerarquia' => '0102', 'cParNombre' => 'PASAJES Y VIATICOS', 'cParDescripcion' => 'PASAJES Y VIATICOS', 'cParValor' => '2', 'nParTipo' => 1],

            ['nParCodigo' => 0, 'nParClase' => '1011', 'cParJerarquia' => '01', 'cParNombre' => '..::  Tipo de viaticos  ::..', 'cParDescripcion' => '..::  Tipo de viaticos  ::..', 'cParValor' => '', 'nParTipo' => 1],
            ['nParCodigo' => 1, 'nParClase' => '1011', 'cParJerarquia' => '0101', 'cParNombre' => 'DENTRO DEL TERRITORIO NACIONAL', 'cParDescripcion' => 'DENTRO DEL TERRITORIO NACIONAL', 'cParValor' => '1', 'nParTipo' => 0],
            ['nParCodigo' => 2, 'nParClase' => '1011', 'cParJerarquia' => '0102', 'cParNombre' => 'FUERA DEL TERRITORIO NACIONAL', 'cParDescripcion' => 'FUERA DEL TERRITORIO NACIONAL', 'cParValor' => '2', 'nParTipo' => 1],
             
            ['nParCodigo' => 0, 'nParClase' => '1012', 'cParJerarquia' => '01', 'cParNombre' => '..::  Medios de Transporte  ::..', 'cParDescripcion' => '..::  Medios de Transporte  ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1012', 'cParJerarquia' => '0101', 'cParNombre' => 'VIA TERRESTRE', 'cParDescripcion' => 'VIA TERRESTRE', 'cParValor' => '1', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1012', 'cParJerarquia' => '0102', 'cParNombre' => 'VIA AEREA', 'cParDescripcion' => 'VIA AEREA', 'cParValor' => '2', 'nParTipo' => 1],
            ['nParCodigo' => 3, 'nParClase' => '1012', 'cParJerarquia' => '0103', 'cParNombre' => 'OTROS', 'cParDescripcion' => 'OTROS', 'cParValor' => '3', 'nParTipo' => 1],

            ['nParCodigo' => 0, 'nParClase' => '1013', 'cParJerarquia' => '01', 'cParNombre' => '..::  Tipo de Categoria  ::..', 'cParDescripcion' => '..::  Tipo de Categoria  ::..', 'cParValor' => '', 'nParTipo' => 0],
            ['nParCodigo' => 1, 'nParClase' => '1013', 'cParJerarquia' => '0101', 'cParNombre' => 'BIEN', 'cParDescripcion' => 'BIEN', 'cParValor' => '1', 'nParTipo' => 1],
            ['nParCodigo' => 2, 'nParClase' => '1013', 'cParJerarquia' => '0102', 'cParNombre' => 'SERVICIO', 'cParDescripcion' => 'SERVICIO', 'cParValor' => '2', 'nParTipo' => 1],
            
             
        ];

        Parameter::insert($parameter);
    }
}
