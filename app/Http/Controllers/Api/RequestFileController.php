<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\RequestFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RequestFileClassifier;
use Illuminate\Support\Facades\Validator;

class RequestFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = RequestFile::select("*")
                        ->with("requestFileClassifier")
                        ->with('person')
                        ->paginate();
        return response()->json($requests);   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'request_type' => 'required|numeric',
                'year' => 'required|numeric',
                'request_date' => 'required_if:request_type,==,1|string|max:10',
                'request_amount' => 'required|numeric',
                'reference_document' => 'required_if:request_type,==,1|nullable|string|max:255',
                'purpose' => 'required_if:request_type,==,1|nullable|string|max:255',
                'justification' => 'required_if:request_type,==,1|nullable|string|max:255',
                'request_file_classifier' => 'required', //request_file_classifier
            ]);

            $validator->sometimes(['viatic_type', 'means_of_transport', 'number_days'], 'required|numeric', function ($input) {
                return $input->request_type == 2;
            });

            $validator->sometimes(['destination', 'departure_date', 'return_date'], 'required|string', function ($input) {
                return $input->request_type == 2;
            });

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            if(empty($request->id)){
                $number_correlative = RequestFile::where('request_type', $request->request_type)->where('year', $request->year)->max('number_correlative') + 1 ?? 1;
                $data["number_correlative"] = $number_correlative;
            }
            
            $requestFileClassifier = $request->request_file_classifier;
            DB::beginTransaction();
            $requestFile = RequestFile::updateOrCreate(['id' => $request->id], $data);
            RequestFileClassifier::where('request_id', $requestFile->id)->delete();
            
            foreach ($requestFileClassifier as &$value) {
                $value["request_id"] = $requestFile->id;
                $value["created_at"] = date('Y-m-d H:i:s');
                $value["updated_at"] = date('Y-m-d H:i:s');
            }

            RequestFileClassifier::insert($requestFileClassifier);

            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "Solicitud registrada con éxito.",
                "data" => $data
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function searchRequestById(Request $request)
    {
        $data = $request->all();        
        $validator = Validator::make($data, [
            'request_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        $request_id_search = $request->request_id;
        $data = RequestFile::select('*')
                ->with('requestFileClassifier')->where('requests.id', $request_id_search)->first();
        return response()->json($data);
    }

    public function searchRequestByCorrelativeAndRequestTypeAndYear(Request $request){
        $data = $request->all();        
        $validator = Validator::make($data, [
            'correlative' => 'required|numeric',
            'request_type_id' => 'required|numeric',
            'year' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        $correlative_search = $request->correlative;
        $request_type_id_search = $request->request_type_id;
        $year_search = $request->year;
        $data = RequestFile::select('*')
                ->with('requestFileClassifier')->where('number_correlative', $correlative_search)->where('request_type', $request_type_id_search)->where('year', $year_search)->first();

        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'No encontrado'], 400);
        }


        // return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryToRender  $deliveryToRender
     * @return \Illuminate\Http\Response
     */
    public function show(RequestFile $requestFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryToRender  $deliveryToRender
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestFile $requestFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryToRender  $deliveryToRender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestFile $requestFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryToRender  $deliveryToRender
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestFile $requestFile)
    {
        //
    }

    public function updateApproval(Request $request){

        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'id' => 'required|numeric',
                'approval' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            
            RequestFile::where('id', $request->id)->update(
                [
                    'approval' => $request->approval,
                ]
            );

            return response()->json([
                "message" => "Rendición cerrada correctamente.",
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }

}
