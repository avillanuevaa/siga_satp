<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViaticRegisterUpdateRequest extends BaseFormRequest
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
            'service_commission_date' => 'nullable|date_format:d/m/Y',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$validator->failed()) {
                $this->parseDate('service_commission_date');
            }
        });
    }

    public function filtered()
    {
        return $this->only([
            'affidavit_description_lost_documents',
            'affidavit_amount_lost_documents',
            'affidavit_amount_undocumented_expenses',
            'service_commission_a',
            'service_commission_from',
            'service_commission_date',
            'service_commission_activities_performed',
            'service_commission_results_obtained',
        ]);
    }


}
