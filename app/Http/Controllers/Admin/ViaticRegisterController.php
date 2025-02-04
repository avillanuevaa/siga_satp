<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Carbon\Carbon;

use App\Models\ViaticRegister;
use App\Models\Settlement;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Requests\ViaticRegisterRequest;
use App\Http\Requests\ViaticRegisterUpdateRequest;
use App\Http\Requests\ViaticRegisterCloseRequest;
use Illuminate\Support\Facades\Validator;


class ViaticRegisterController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = ViaticRegister::select("*")
                        ->with("settlement")
                        ->with("user.person")
                        ->paginate(20);

        session(['previous_url_viatic_register' => url()->full()]);

        return response()->view('admin.viatic_register.index', [
            'items' => $data
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
        if ($request->has(['correlative_search', 'year_search'])) {
        
            $validator = Validator::make($request->all(), [
                'correlative_search' => 'required|numeric',
                'year_search' => 'required|numeric',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $correlative = $request->input('correlative_search');
            $year = $request->input('year_search');
            $requestTypeId = 2;

            $settlement = Settlement::select('*')
                    ->with('settlementClassifier')
                    ->with('requestFile')
                    ->with('requestFile.person')
                    ->where('settlements.request_type', $requestTypeId)
                    ->where('settlements.number_correlative', $correlative)
                    ->where('year', $year)
                    ->first();



            if ($settlement) {
                $viaticsTypes = Parameter::viaticsType();
                $transportationsMeans = Parameter::transportationsMean();
                
                $viaticRegister = new ViaticRegister;
                $viaticRegister->fill(Arr::except($settlement->getAttributes(), ['id']));
                $viaticRegister->settlement_id = encrypt($settlement->id);
                $viaticRegister->number = $settlement->number_correlative;
                $viaticRegister->approved_amount = $settlement->approved_amount;
                $viaticRegister->responsible = ($settlement->person->document_number ?? '') . ' - '. ($settlement->person->name ?? '') . ' ' . ($settlement->person->lastname ?? '');
                $viaticRegister->authorization_date = $settlement->authorization_date;
                $viaticRegister->authorization_detail = $settlement->authorization_detail;
                $viaticRegister->viatic_type = $viaticsTypes[$settlement->viatic_type] ?? null;
                $viaticRegister->destination = $settlement->destination;
                $viaticRegister->means_of_transport = $transportationsMeans[$settlement->means_of_transport] ?? null;
                $viaticRegister->format_number_two = $settlement->format_number_two;
                $viaticRegister->departure_date = $settlement->departure_date;
                $viaticRegister->number_days = $settlement->number_days;
                $viaticRegister->return_date = $settlement->return_date;
                $viaticRegister->reason = $settlement->reason;
                
                return response()->view('admin.viatic_register.create', [
                    'viaticRegister' => $viaticRegister,
                    'searchRequest' => json_decode(json_encode($request->all()), false),
                ]);

            }else{
                $notFound = true;
            }
        }

        return response()->view('admin.viatic_register.create', [
            'searchRequest' => json_decode(json_encode($request->all()), false),
            'notFound' => $notFound ?? false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ViaticRegisterRequest $request)
    {
        //
        $data = $request->all();
        $settlement = Settlement::find($data['settlement_id']);
        $data['year'] = $settlement->year;
        $data['number'] = $settlement->number_correlative;
        $data['user_id'] = auth()->user()->id;
        ViaticRegister::create($data);
        return redirect()->to(session('previous_url_viatic_register'))
                ->with([
                    'notif' => [
                        'message' => 'Viatico aperturado correctamente',
                        'icon' => 'success'
                    ],
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ViaticRegister  $viaticRegister
     * @return \Illuminate\Http\Response
     */
    public function show(ViaticRegister $viaticRegister)
    {
        //
        // $viaticRegister->makeHidden(['user', 'person', 'settlement']);

        $viaticRegister->viatic_register_id = encrypt($viaticRegister->id);
        $responsibleName = $viaticRegister?->user?->person?->name .' ' . $viaticRegister?->user?->person?->lastname;
        $responsibleDocumentNumber = $viaticRegister?->user?->person?->document_number;
        $viaticRegister->responsible = "$responsibleDocumentNumber - $responsibleName";
        $viaticRegister->approved_amount = $viaticRegister?->settlement?->approved_amount;
        $viaticRegister->authorization_date = $viaticRegister?->settlement?->authorization_date;
        $viaticRegister->authorization_detail = $viaticRegister?->settlement?->authorization_detail;
        $viaticRegister->reason = $viaticRegister?->settlement?->reason;

        $viaticRegister->viatic_type = $viaticRegister?->settlement?->viaticType?->cParNombre;
        $viaticRegister->destination = $viaticRegister?->settlement?->destination;
        $viaticRegister->means_of_transport = $viaticRegister?->settlement?->transportationsMeansType?->cParNombre;
        $viaticRegister->format_number_two = $viaticRegister?->settlement?->format_number_two;
        $viaticRegister->departure_date = $viaticRegister?->settlement?->departure_date;
        $viaticRegister->number_days = $viaticRegister?->settlement?->number_days;
        $viaticRegister->return_date = $viaticRegister?->settlement?->return_date;


        return response()->json($viaticRegister);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ViaticRegister  $viaticRegister
     * @return \Illuminate\Http\Response
     */
    public function edit(ViaticRegister $viaticRegister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ViaticRegister  $viaticRegister
     * @return \Illuminate\Http\Response
     */
    public function update(ViaticRegisterUpdateRequest $request, ViaticRegister $viaticRegister)
    {
        //
        $data = $request->filtered();
        $viaticRegister->update($data);

        return redirect()->to(session('previous_url_viatic_register_detail'))
                ->with([
                    'notif' => [
                        'message' => 'Actualizado correctamente',
                        'icon' => 'success'
                    ],
                ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ViaticRegister  $viaticRegister
     * @return \Illuminate\Http\Response
     */
    public function destroy(ViaticRegister $viaticRegister)
    {
        //
    }

    public function close(ViaticRegisterCloseRequest $request)
    {
        $data = $request->all();

        ViaticRegister::where('id', $data['viatic_register_id'])->update(
            [
                'closing_date' => $data['closing_date'],
                'amount_to_pay' => $data['amount_to_pay'],
                'amount_to_returned' => $data['amount_to_returned'],
                'surrender_report' => $data['surrender_report'],
                'closed' => 1,
            ]
        );

        session()->flash('notif', [
            'message' => 'Viatico cerrado correctamente',
            'icon' => 'success'
        ]);

        return response()->json([
            'success' => true,
            "message" => "Viatico cerrado correctamente.",
        ]);
    }

}
