<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashRegisterRequest extends BaseFormRequest
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
            //
            'amount' => 'required|numeric',
            'opening_date' => 'required|required|date_format:d/m/Y',

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$validator->failed()) {
                $this->parseDate('opening_date');
            }

        });
    }
}
