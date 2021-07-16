<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use \Response;
class PagoRequest extends Request
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
        'name'=>'required|unique:pagos'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo tipo de pago es obligatorio',
            'name.unique' => 'Ya existe el tipo de pago'
        ];
    }
}
