<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;



class CashRegisterCloseRequest extends BaseFormRequest
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
            'cash_register_id' => 'required',
            'closing_date' => 'required|date_format:d/m/Y',
            'amount_to_pay' => 'required|numeric',
            'surrender_report' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'error' => true,
            'message' => 'Error en la validación de los datos.',
            'errors' => $validator->errors(),
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $cashRegisterId = $this->input('cash_register_id');

            if (!empty($cashRegisterId)) {
                try {
                    $cashRegisterId = Crypt::decrypt($cashRegisterId);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $validator->errors()->add('cash_register_id', 'Error al guardar');
                }
            }

            if (!$validator->failed()) {

                $this->parseDate('closing_date');

                // Asigna el valor desencriptado de settlement_id al formulario
                $this->merge(['cash_register_id' => $cashRegisterId]);

                //Remove
                $this->request->remove('year');
                $this->request->remove('number');
                $this->request->remove('responsible');
                $this->request->remove('opening_date');
                $this->request->remove('amount');
            }

        });
    }
}