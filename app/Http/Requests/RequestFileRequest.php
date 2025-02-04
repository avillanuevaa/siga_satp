<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestFileRequest extends FormRequest
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
            'request_type' => 'required|numeric',
            'year' => 'required|numeric',
            'request_date' => 'required_if:request_type,==,1|date_format:d/m/Y',
            'request_amount' => 'required|numeric',
            'reference_document' => 'required_if:request_type,==,1|nullable|string|max:255',
            'purpose' => 'required_if:request_type,==,1|nullable|string|max:255',
            'justification' => 'required_if:request_type,==,1|nullable|string|max:255',
            'requestFileClassifier' => 'required|array|min:1', //request_file_classifier
        ];

        $request_type = $this->input('request_type');

        if($request_type == 2){
            $rules['viatic_type'] = 'required|numeric';
            $rules['means_of_transport'] = 'required|numeric';
            $rules['number_days'] = 'required|numeric';
            
            $rules['destination'] = 'required|string';
            $rules['departure_date'] = 'required|date_format:d/m/Y';
            $rules['return_date'] = 'required|date_format:d/m/Y';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$validator->failed()) {
                $this->parseDate('request_date');
                $this->parseDate('departure_date');
                $this->parseDate('return_date');

                $this->merge([
                    'have_retention' => $this->input('have_retention') === 'on',
                    'retention' => in_array($this->input('type_new'), ['R', 'N']) ? $this->input('retention') : 0.00,
                ]);

            }
        });
    }

    protected function prepareForValidation()
    {
        $requestFileClassifier = $this->input('requestFileClassifier');
    
        if (is_string($requestFileClassifier)) {
            $decodedArray = json_decode($requestFileClassifier, true);
            
            if (is_array($decodedArray)) {
                $this->merge(['requestFileClassifier' => $decodedArray]);
            }
        }

    }

    public function messages()
    {
        return [
            'justification.required_if' => 'El campo justificación es requerido cuando el campo tipo de solicitud es Encargo',
            'requestFileClassifier.required' => 'Debe ingresar al menos un clasificador '
        ];
    }

}
