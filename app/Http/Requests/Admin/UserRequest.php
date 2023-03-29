<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'bn_name' => 'required',
            'email' => 'nullable|unique:users,email,'.@$this->user->id,
            'mobile' => 'nullable|unique:users,mobile,'.@$this->user->id,
            'employer_id' => 'nullable|unique:users,employer_id,'.@$this->user->id,
            'password' => 'nullable|min:6',
            'password_confirm' => 'required_with:password,true|same:password',
            'status' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'employer_id' => trans('user.label_employer_id'),
        ];
    }
}
