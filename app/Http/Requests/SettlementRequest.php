<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettlementRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'request_id' => 'required|numeric',
            'request_type' => 'required|numeric',
            'number_correlative' => 'required|numeric',
            'year' => 'required|numeric',
            'approved_amount' => 'required|numeric',
            'budget_certificate' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'authorization_date' => 'required|date_format:d/m/Y',
            'authorization_detail' => 'required|string|max:255',
            'settlementClassifier' => 'required|array|min:1'
        ];

        $request_type = $this->input('request_type');

        if($request_type == 2){
            $rules['viatic_type'] = 'required|numeric';
            $rules['means_of_transport'] = 'required|numeric';
            $rules['number_days'] = 'required|numeric';
            
            $rules['destination'] = 'required|string';
            $rules['format_number_two'] = 'required|string';

            $rules['departure_date'] = 'required|date_format:d/m/Y';
            $rules['return_date'] = 'required|date_format:d/m/Y';


        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $settlementClassifiers = $this->input('settlementClassifier');
            $approvedAmount = $this->input('approved_amount');

            $totalGoals = 0;
        
            // Suma los goals de todos los settlementClassifiers
            foreach ($settlementClassifiers as $classifier) {
                $totalGoals += $classifier['goal_one'] + $classifier['goal_two'] + $classifier['goal_three'];
            }
            
            if ($totalGoals != $approvedAmount) {
                $validator->errors()->add('settlementClassifier', 'La suma de las metas de los clasificadores no coincide con el monto aprobado');
            }


            if (!$validator->failed()) {
                $this->parseDate('authorization_date');
                $this->parseDate('departure_date');
                $this->parseDate('return_date');
            }

        });
    }

    protected function prepareForValidation()
    {
        $settlementClassifier = $this->input('settlementClassifier');
    
        if (is_string($settlementClassifier)) {
            $decodedArray = json_decode($settlementClassifier, true);
            
            if (is_array($decodedArray)) {
                $this->merge(['settlementClassifier' => $decodedArray]);
            }
        }

    }

    public function messages()
    {
        return [
            'settlementClassifier.required' => 'Debe ingresar al menos un clasificador '
        ];
    }
}
