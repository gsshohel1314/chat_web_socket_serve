<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class DistrictRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|unique:districts,name,'.@$this->district->id,
            'bn_name' => 'required|unique:districts,bn_name,'.@$this->district->id,
            'division_id' => 'required',
        ];
    }

    // public function attributes()
    // {
    //     return [
    //         'division_id' => trans('district.code'),
    //     ];
    // }
}
