<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoaaaRequest extends FormRequest
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
        // da 3shan y-insert w validate el add request
        return [
            "name"=>"require",    //aw nullable
        ];
    }
}
