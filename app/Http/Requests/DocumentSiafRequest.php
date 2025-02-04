<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentSiafRequest extends BaseFormRequest
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
            'date' => 'required|date_format:d/m/Y',
            'type_new' => 'required',
            'serie' => 'required',
            'number' => 'required',
            'ruc' => 'required',
            'business_name' => 'required',
            'taxable_basis' => 'required',
            'igv' => 'required',
            'untaxed_basis' => 'required',
            'impbp' => 'required',
            'other_concepts' => 'required',
            'amount' => 'required',
            'payment_date' => 'required|date_format:d/m/Y',
            'doc_code' => 'required',
            'num_doc' => 'required',
            'ha_1' => 'required|numeric',
            'ha_2' => 'required|numeric',
            'ha_3' => 'required|numeric',
            'detraction_date' => 'nullable|date_format:d/m/Y',

        ];

        $type_new = $this->input('type_new');

        if (in_array($type_new, ['R', 'N'])) {
            $rules['total_honorary'] = 'required|numeric';
            $rules['retention'] = 'required|numeric';
            $rules['net_honorary'] = 'required|numeric';
            $rules['last_name'] = 'required|string';
            $rules['mother_last_name'] = 'required|string';
            $rules['name'] = 'required|string';

        }

        if ($type_new == '07') {
            $rules['doc_modify_date_of_issue'] = 'required|date_format:d/m/Y';
            $rules['doc_modify_type'] = 'required';
            $rules['doc_modify_serie'] = 'required';
            $rules['doc_modify_number'] = 'required';
        }

         if ($this->isStoreMethod()) {
            $rules['siaf'] = 'required|numeric';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$validator->failed()) {
                $this->parseDate('date');
                $this->parseDate('payment_date');
                $this->parseDate('doc_modify_date_of_issue');
                $this->parseDate('detraction_date');

                $this->merge([
                    'have_retention' => $this->input('have_retention') === 'on',
                    'retention' => in_array($this->input('type_new'), ['R', 'N']) ? $this->input('retention') : 0.00,
                ]);

            }
        });
    }

    private function isStoreMethod()
    {
        return $this->route()->getActionMethod() == 'store';
    }
}
