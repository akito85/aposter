<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadParticipantsRequest extends FormRequest
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
            // file validation
            'data_participants'  => 'required|max:113664'
        ];
    }

    /**
     * Get the error message upon request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // file validation
            'data_participants.required'  => 'Please input the file excel (xls/xlsx)'
        ];
    }
}