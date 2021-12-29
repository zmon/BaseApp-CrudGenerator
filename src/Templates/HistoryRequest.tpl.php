<?php

namespace App\Http\Requests\[[model_uc]];

use Illuminate\Foundation\Http\FormRequest;

class [[model_uc]]HistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user()->can('[[route_path]] history');
    }

    // overwrite parent method to redirect unauthorized requests
    protected function failedAuthorization()
    {
        session()->flash('flash_error_message', "You are not authorized to view an [[model_uc]]'s history.");
        abort(redirect()->route('[[route_path]].index'));
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
