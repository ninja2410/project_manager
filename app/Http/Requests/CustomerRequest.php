<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use \Response;
class CustomerRequest extends Request {

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
            'nit_customer'=>'required',
			'name' => 'required',
			'email' => 'email|unique:customers',
			'avatar' => 'mimes:jpeg,bmp,png'
		];
	}

	public function forbiddenResponse()
    {
        return Response::make('Sorry!',403);
    }

    public function messages()
    {
        return [
            'avatar.mimes' => 'Not a valid file type. Valid types include jpeg, bmp and png.',
			'nit_customer.required'=> 'Nit del cliente requerido',
			'name.required' =>'Nombre del cliente requerido',
			'nit_customer.unique' =>'El Nit ya existe, verifique si esta creando un cliente duplicado.',
			'customer_code.unique' =>'El código de cliente ya existe.'
        ];
    }

}
