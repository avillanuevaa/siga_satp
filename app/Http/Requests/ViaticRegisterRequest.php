<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class ViaticRegisterRequest extends BaseFormRequest
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
            'opening_date' => 'required|date_format:d/m/Y',
            'siaf_date' => 'required|date_format:d/m/Y',
            'siaf_number' => 'required|numeric',
            'voucher_date' => 'required|date_format:d/m/Y',
            'voucher_number' => 'required|numeric',
            'order_pay_electronic_date' => 'required|date_format:d/m/Y',
            'settlement' => 'required',

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $settlementId = $this->input('settlement');

            if (!empty($settlementId)) {
                try {
                    $settlementId = Crypt::decrypt($settlementId);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $validator->errors()->add('settlement', 'Liquidación no válida, vuelva a buscar.');
                }
            }

            if (!$validator->failed()) {

                $this->parseDate('opening_date');
                $this->parseDate('siaf_date');
                $this->parseDate('voucher_date');
                $this->parseDate('order_pay_electronic_date');

                // Asigna el valor desencriptado de settlement_id al formulario
                $this->merge(['settlement_id' => $settlementId]);

                //Remove
                $this->request->remove('settlement');
                $this->request->remove('year');
                $this->request->remove('responsible');
                $this->request->remove('number');
                $this->request->remove('approved_amount');
                $this->request->remove('authorization_date');
                $this->request->remove('authorization_detail');
                $this->request->remove('reason');
            }

        });
    }

    public function messages()
    {
        return [
            'settlement.required' => 'Primero tiene que búscar una liquidación de tipo Pasajes y viaticos'
        ];
    }
}
