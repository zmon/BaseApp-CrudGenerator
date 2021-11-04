<?php

namespace App\Http\Requests\[[model_uc]];

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class [[model_uc]]FormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('[[model_singular]]')) {  // If ID we must be changing an existing record
            return Auth::user()->can('[[model_singular]] update');
        } else {  // If not we must be adding one
            return Auth::user()->can('[[model_singular]] add');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $[[model_singular]] = $this->route('[[model_singular]]');

        $rules = [
            //  Ignore duplicate email if it is this record
            //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',


            'id' => 'numeric',
[[foreach:columns]]
    [[if:i.name!='name']]
        '[[i.name]]' => '[[i.validation]]',
    [[endif]]
[[endforeach]]
            'reason_for_change' => 'required|string',

        ];

        if ($this->route('[[model_singular]]')) {  // If ID we must be changing an existing record
            $rules['name'] = 'required|min:3|nullable|string|max:120|unique:[[model_plural]],name,' . $[[model_singular]]->id;
//            $rules['alias'] = 'required|string|max:120|unique:[[model_plural]],alias,' . $[[model_singular]]->id;
        } else {  // If not we must be adding one
            $rules['name'] = 'required|min:3|nullable|string|max:120|unique:[[model_plural]],name';
//            $rules['alias'] = 'required|string|max:120|unique:[[model_plural]],alias';
        }

        return $rules;
    }
}
