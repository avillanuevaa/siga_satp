<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;


class BaseFormRequest extends FormRequest
{
    protected function parseDate($field)
    {
        $date = $this->input($field);

        if ($date) {
            $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
            if ($parsedDate->isValid()) {
                $this->merge([$field => $parsedDate->format('Y-m-d')]);
            }
        }
    }
}
