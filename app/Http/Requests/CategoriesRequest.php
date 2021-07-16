<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use \Response;

class CategoriesRequest extends Request
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
            'name'=>'required|unique:categories'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre de la categoria es obligatorio',
            'name.unique' => 'Ya existe la categoria'
        ];
    }
}
