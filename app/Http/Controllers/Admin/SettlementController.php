<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Arr;

use App\Models\Settlement;
use App\Http\Requests\SettlementRequest;
use App\Models\Parameter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;



class SettlementController extends AdminController
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

        $data = Settlement::select("*")
                        ->with("settlementClassifier")
                        ->with("requestFile")
                        ->with('person')
                        ->with('requestType')
                        ->when($fullName, function($query, $fullName) {
                            return $query->whereHas('person', function ($query) use ($fullName) {
                                $query->where('name', 'LIKE', "%{$fullName}%")
                                        ->orWhere('lastname', 'LIKE', "%{$fullName}%");
                            });
                        })
                        ->paginate(20);

        return response()->view('admin.settlement.index', [
            'items' => $data,
            'request' => $request
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $requestFile = session('requestFile');

        if ($requestFile){
            $settlement = new Settlement;
            
            $settlement->fill(Arr::except($requestFile->getAttributes(), ['id']));
            $settlement->request_id = $requestFile->id;
            $requestFileClassifier = $requestFile->requestFileClassifier;
            $settlementClassifier = $requestFileClassifier->map(function ($item) {
                return Arr::except($item->getAttributes(), ['id', 'id', 'request_id', 'created_at', 'updated_at']);
            });
            $settlement->setRelation('settlementClassifier', $settlementClassifier);
        }

        $searchRequest = session('searchRequest');

        return response()->view('admin.settlement.create', [
            'requestsTypes' => Parameter::requestsType(),
            'viaticsTypes' => Parameter::viaticsType(),
            'transportationsMeans' => Parameter::transportationsMean(),
            'responsible' => auth()->user(),
            'settlement' => $settlement ?? null,
            'searchRequest' => json_decode(json_encode($searchRequest), false)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettlementRequest $request)
    {
        //
        try {
            $data = $request->all();

            $data['person_id'] = auth()->user()->id;

            DB::beginTransaction();

            $settlement = Settlement::create($data);

            $settlementClassifier = $request->settlementClassifier;

            $settlement->settlementClassifier()->delete();

            foreach ($settlementClassifier as $item) {
                $settlement->settlementClassifier()->create([
                    'financial_classifier_id' => $item['financial_classifier_id'],
                    'code_classify' => $item['code_classify'],
                    'name_classify' => $item['name_classify'],
                    'goal_one' => $item['goal_one'],
                    'goal_two' => $item['goal_two'],
                    'goal_three' => $item['goal_three']
                ]);
            }
            
            $settlement->update($data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->route('settlements.index')
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
     * @param  \App\Models\Settlement  $settlement
     * @return \Illuminate\Http\Response
     */
    public function show(Settlement $settlement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settlement  $settlement
     * @return \Illuminate\Http\Response
     */
    public function edit(Settlement $settlement)
    {
        //
        $settlement->load('settlementClassifier');
        $settlement->load('person');

        return response()->view('admin.settlement.edit', [
            'requestsTypes' => Parameter::requestsType(), //scopeRequestsType
            'viaticsTypes' => Parameter::viaticsType(),
            'transportationsMeans' => Parameter::transportationsMean(),
            'settlement' => $settlement,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settlement  $settlement
     * @return \Illuminate\Http\Response
     */
    public function update(SettlementRequest $request, Settlement $settlement)
    {
        //

        try {

            $data = $request->all();

            DB::beginTransaction();

            $settlementClassifier = $request->settlementClassifier;

            $settlement->settlementClassifier()->delete();

            foreach ($settlementClassifier as $item) {
                $settlement->settlementClassifier()->create([
                    'financial_classifier_id' => $item['financial_classifier_id'],
                    'code_classify' => $item['code_classify'],
                    'name_classify' => $item['name_classify'],
                    'goal_one' => $item['goal_one'],
                    'goal_two' => $item['goal_two'],
                    'goal_three' => $item['goal_three']
                ]);
            }
            
            $settlement->update($data);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

        return redirect()->route('settlements.index')
                ->with([
                    'notif' => [
                        'message' => 'Solicitud registrada satisfactoriamente.',
                        'icon' => 'success'
                    ],
                ]);
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

    public function updateApproval(Settlement $settlement){

        try {

            $settlement->approval = 1;
            $settlement->update();

            return response()->json([
                "message" => "Liquidación actualizada correctamente.",
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
