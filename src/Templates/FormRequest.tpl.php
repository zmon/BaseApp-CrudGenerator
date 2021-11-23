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
        if ($this->route('[[route_path]]')) {  // If ID we must be changing an existing record
            return Auth::user()->can('[[route_path]] update');
        } else {  // If not we must be adding one
            return Auth::user()->can('[[route_path]] add');
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
            $rules['[[name_field]]'] = 'required|min:3|nullable|string|max:120|unique:[[tablename]],[[name_field]],' . $[[model_singular]]->id;
//            $rules['alias'] = 'required|string|max:120|unique:[[model_plural]],alias,' . $[[model_singular]]->id;
        } else {  // If not we must be adding one
            $rules['[[name_field]]'] = 'required|min:3|nullable|string|max:120|unique:[[tablename]],[[name_field]]';
//            $rules['alias'] = 'required|string|max:120|unique:[[model_plural]],alias';
        }

        return $rules;
    }
}
