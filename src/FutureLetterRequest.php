<?php

namespace Buzkall\FutureLetters;

use Illuminate\Foundation\Http\FormRequest;

class FutureLetterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'email'        => 'required|email',
            'subject'      => 'required|min:3',
            'message'      => 'required|min:3',
            'sending_date' => 'required|date_format:d/m/Y H:i|after:today',
        ];
    }
}
