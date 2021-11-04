<?php

namespace App\Http\Requests\[[model_uc]];

use Illuminate\Foundation\Http\FormRequest;

class [[model_uc]]EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user()->can('[[model_singular]] edit');
    }

    // overwrite parent method to redirect unauthorized requests
    protected function failedAuthorization()
    {
        session()->flash('flash_error_message', "You are not authorized to change an [[model_uc]]'s details.");
        abort(redirect()->route('[[model_singular]].index'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
