<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViaticRegisterDetailRequest extends FormRequest
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
        return [
            'issue_date' => 'required|required|date_format:d/m/Y',
            'issue_type' => 'required|numeric',
            'issue_serie' => 'required',
            'issue_number' => 'required|numeric',
            'supplier_type' => 'required|numeric',
            'supplier_number' => 'required|numeric',
            'supplier_name' => 'required',
            'taxed_base' => 'required|numeric',
            'igv' => 'required|numeric',
            'untaxed_base' => 'required|numeric',
            'impbp' => 'required|numeric',
            'other_concepts' => 'required|numeric',
            'total' => 'required|numeric',
            'cost_center_code' => 'required|numeric',
            'cost_center_description' => 'required',
            'goal_code' => 'required|numeric',
            'goal_description' => 'required',
            'classifier_code' => 'required',
            'classifier_descripcion' => 'required',
            'classifier_amount' => 'required|numeric',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$validator->failed()) {
                $this->parseDate('issue_date');
            }

        });
    }
}
