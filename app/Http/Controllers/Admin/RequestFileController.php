<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\RequestFile;
use App\Http\Requests\RequestFileRequest;
use App\Models\Parameter;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class RequestFileController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $fullName = $request->fullName;
        $data = RequestFile::select("*")
                ->with("requestFileClassifier")
                ->with('person')
                ->with('requestType')
                ->when($fullName, function($query, $fullName) {
                    return $query->whereHas('person', function ($query) use ($fullName) {
                        $query->where(DB::raw('CONCAT(name," ",lastname)'), 'LIKE', "%{$fullName}%");
                    });
                })
                ->paginate(20);

        return response()->view('admin.request_file.index', [
            'items' => $data,
            'request' => $request
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return response()->view('admin.request_file.create', [
            'requestsTypes' => Parameter::requestsType(), //scopeRequestsType
            'viaticsTypes' => Parameter::viaticsType(),
            'transportationsMeans' => Parameter::transportationsMean(),
            'responsible' => auth()->user(),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestFileRequest $request)
    {
        //
        $data = $request->all();

        try {
            
            $number_correlative = RequestFile::where('request_type', $request->request_type)->where('year', $request->year)->max('number_correlative') + 1 ?? 1;
            $data["number_correlative"] = $number_correlative;
    
            $data['person_id'] = auth()->user()->id;

            $requestFile = RequestFile::create($data);

            $requestFileClassifier = $request->requestFileClassifier;

            DB::beginTransaction();

            $requestFile->requestFileClassifier()->delete();

            foreach ($requestFileClassifier as $item) {
                $requestFile->requestFileClassifier()->create([
                    'financial_classifier_id' => $item['financial_classifier_id'],
                    'code_classify' => $item['code_classify'],
                    'name_classify' => $item['name_classify'],
                    'goal_one' => $item['goal_one'],
                    'goal_two' => $item['goal_two'],
                    'goal_three' => $item['goal_three']
                ]);
            }
            
            $requestFile->update($data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->route('requestFiles.index')
                ->with([
                    'notif' => [
                        'message' => 'Solicitud registrada satisfactoriamente.',
                        'icon' => 'success'
                    ],
                ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RequestFile  $requestFile
     * @return \Illuminate\Http\Response
     */
    public function show(RequestFile $requestFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequestFile  $requestFile
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestFile $requestFile)
    {
        //
        // Cargar la relación "requestFileClassifier"
        $requestFile->load('requestFileClassifier');
        $requestFile->load('person');

        return response()->view('admin.request_file.edit', [
            'requestsTypes' => Parameter::requestsType(), //scopeRequestsType
            'viaticsTypes' => Parameter::viaticsType(),
            'transportationsMeans' => Parameter::transportationsMean(),
            'requestFile' => $requestFile,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestFile  $requestFile
     * @return \Illuminate\Http\Response
     */
    public function update(RequestFileRequest $request, RequestFile $requestFile)
    {
        //
        $data = $request->all();

        $requestFileClassifier = $request->requestFileClassifier;

        try {

            DB::beginTransaction();

            $requestFile->requestFileClassifier()->delete();

            foreach ($requestFileClassifier as $item) {
                $requestFile->requestFileClassifier()->create([
                    'financial_classifier_id' => $item['financial_classifier_id'],
                    'code_classify' => $item['code_classify'],
                    'name_classify' => $item['name_classify'],
                    'goal_one' => $item['goal_one'],
                    'goal_two' => $item['goal_two'],
                    'goal_three' => $item['goal_three']
                ]);
            }
            
            $requestFile->update($data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->route('requestFiles.index')
                ->with([
                    'notif' => [
                        'message' => 'Solicitud actualizada satisfactoriamente.',
                        'icon' => 'success'
                    ],
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RequestFile  $requestFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestFile $requestFile)
    {
        //
    }


    public function updateApproval(RequestFile $requestFile){

        try {

            $requestFile->approval = 1;
            $requestFile->update();

            return response()->json([
                "message" => "solicitud actualizada correctamente.",
            ]);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }


    public function searchRequestByCorrelativeAndRequestTypeAndYear(Request $request){


        $validator = Validator::make($request->all(), [
            'correlative_search' => 'required|numeric',
            'request_type_search' => 'required|numeric',
            'year_search' => 'required|numeric',
        ]);
        

        if ($validator->fails()) {
            return redirect()->route('settlements.create')
                ->withErrors($validator)
                ->withInput();
        }

        $correlative = $request->input('correlative_search');
        $requestTypeId = $request->input('request_type_search');
        $year = $request->input('year_search');

        $requestFile = RequestFile::where('number_correlative', $correlative)
                ->where('request_type', $requestTypeId)
                ->where('year', $year)
                ->with('requestFileClassifier')
                ->first();

        if ($requestFile){
            return redirect()->route('settlements.create')
                        ->with('requestFile', $requestFile)
                        ->with('searchRequest', $request->all());
        }else{
            return redirect()->route('settlements.create')
                        ->with('searchRequest', $request->all())
                        ->with([
                            'notif' => [
                                'message' => 'No se encontro solicitud.',
                                'icon' => 'error'
                            ]
                        ]);
        }

    }

}
