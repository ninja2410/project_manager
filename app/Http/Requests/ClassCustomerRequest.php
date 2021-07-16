<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use \Response;

class ClassCustomerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }
    public function forbiddenResponse()
    {
        return Response::make('Sorry!',403);
    }
    public function messages()
    {
        return [
            'name.unique' =>'Ya existe una clase con este número de atrasos.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'arrears'=>'required|unique'
        ];
    }
}
