<?php

namespace App\Http\Requests\[[model_uc]];

use Illuminate\Foundation\Http\FormRequest;

class [[model_uc]]APIIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user()->can('[[model_singular]] index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => [
                'numeric',
            ],
            'column' => [
                'nullable',
                'string',
            ],
            'direction' => [
                'numeric',
            ],
            'keyword' => [
                'string',
            ],
        ];
    }
}
