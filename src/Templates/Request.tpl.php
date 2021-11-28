<?php

namespace [[appns]]Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class [[model_uc]]Request extends FormRequest
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
            return Auth::user()->can('[[route_path]] create');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('[[route_path]]');

        $rules = [
         //  Ignore duplicate email if it is this record
         //   'email' => 'required|string|email|unique:invites,email,' . $id . '|unique:users|max:191',
            'id' => 'numeric',
[[foreach:columns]]
[[if:i.name!='name']]
            '[[i.name]]' => '[[i.validation]]',
[[endif]]
[[endforeach]]

        ];

[[foreach:columns]]
[[if:i.name=='name']]
                if ($this->route('[[route_path]]')) {  // If ID we must be changing an existing record
                    $rules['name'] = '[[i.validation]]|unique:[[tablename]],name,' . $id;
                } else {  // If not we must be adding one
                    $rules['name'] = '[[i.validation]]|unique:[[tablename]]';
                }
[[endif]]
[[endforeach]]

        return $rules;
    }
}


