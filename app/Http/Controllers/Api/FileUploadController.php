<?php

namespace App\Http\Controllers\Api;

use App\Models\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Imports\DocumentsSiafImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FileUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function uploadExcelSiaf(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'file' => 'required|file|mimes:xls,xlsx'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            DB::beginTransaction(); // Tell Laravel all the code beneath this is a transaction

            $file = $request->file('file');

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $newfilename = date('dmYHis') . "." . $extension;
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes
            $today = date('dmY');
            $location = "uploads/excel-siaf/" . $today; //Created an "uploads" folder for that

            $fileUpload = FileUpload::create([
                'original_name' => $filename,
                'name' => $newfilename,
                'extension' => $extension,
                'size' => $fileSize,
                'path' => $location,
                'type' => 1
            ]);

            Excel::import($newsDocumentsSiaf = new DocumentsSiafImport($fileUpload->id), $file);

            $file->move($location, $newfilename);

            DB::commit(); // Tell Laravel this transacion's all good and it can persist to DB

            return response()->json([
                'message' => $newsDocumentsSiaf->getRowCount() . " filas se importaron correctamente.",
            ], 200);
        } catch (\Exception $exp) {
            DB::rollBack();
            return response([
                'message' => $exp->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }
}
