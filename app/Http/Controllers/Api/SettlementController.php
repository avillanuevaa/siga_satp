<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Models\SettlementClassifier;
use App\Models\RequestFile;

class SettlementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settlements = Settlement::select("*")
                        ->with("settlementClassifier")
                        ->with("requestFile")
                        ->with('person')
                        ->paginate();
        return response()->json($settlements);
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
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'request_type' => 'required|numeric',
                'number_correlative' => 'required|numeric',
                'year' => 'required|numeric',
                'approved_amount' => 'required|numeric',
                'budget_certificate' => 'required|string|max:255',
                'reason' => 'required|string|max:255',
                'authorization_date' => 'string|max:10',
                'authorization_detail' => 'required|string|max:255',
                'settlementClassifier' => 'required',
            ]);

            $validator->sometimes(['viatic_type', 'means_of_transport', 'number_days'], 'required|numeric', function ($input) {
                return $input->request_type == 2;
            });

            $validator->sometimes(['destination', 'departure_date', 'return_date', 'format_number_two'], 'required|string', function ($input) {
                return $input->request_type == 2;
            });

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $settlementClassifier = $request->settlementClassifier;

            DB::beginTransaction();
            $settlement = Settlement::updateOrCreate(['id' => $request->id], $data);
            SettlementClassifier::where('settlement_id', $settlement->id)->delete();

            foreach ($settlementClassifier as &$value) {
                $value["settlement_id"] = $settlement->id;
                $value["created_at"] = date('Y-m-d H:i:s');
                $value["updated_at"] = date('Y-m-d H:i:s');
            }
            SettlementClassifier::insert($settlementClassifier);

            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Liquidación registrada con éxito.",
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settlement  $settlement
     * @return \Illuminate\Http\Response
     */
    public function show(Settlement $settlement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settlement  $settlement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Settlement $settlement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settlement  $settlement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Settlement $settlement)
    {
        //
    }


    public function getSettlementById(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'settlement_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $serach_id = $request->settlement_id;
        $data = Settlement::select('*')
            ->with('settlementClassifier')->where('id', $serach_id)->first();
        return response()->json($data);
    }

    public function getSettlementByCorrelativeAndRequestTypeAndYear(Request $request)
    {
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
        $data = Settlement::select('*')
            ->with('settlementClassifier')->with('requestFile')->with('requestFile.person')->where('settlements.request_type', $request_type_id_search)->where('settlements.number_correlative', $correlative_search)->where('year', $year_search)->first();
        // $data = $data->makeHidden('id');
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'No encontrado'], 400);
        }
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
            
            Settlement::where('id', $request->id)->update(
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
