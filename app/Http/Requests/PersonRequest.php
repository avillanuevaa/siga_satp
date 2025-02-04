<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends BaseFormRequest
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
            'name' => 'required|string',
            'lastname' => 'required|string',
            'document_type_id' => 'required|numeric',
            'document_number' => 'required|string|max:11',
            'address' => 'string',
            'phone' => 'numeric',
            'office' => 'required|array|min:1',
        ];
    }
}
