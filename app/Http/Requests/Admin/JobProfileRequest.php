<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class JobProfileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if (request()->auth_id){
            return [
                'first_name' => 'required',
                'middle_name' => 'required',
                'last_name' => 'required',
                'blood_group' => 'required',
                'date_of_birth' => 'required',
                'phone_no' => 'required',
                'office_number' => 'nullable',
                'nid' => 'required|unique:users,nid,'.@$this->user->id,
                'address' => 'required',
                'gender' => 'required',
                'ewu_id_no' => 'required',
                'employment_status' => 'required',
            ];
        } else{
            return [
                'first_name' => 'required',
                'middle_name' => 'required',
                'last_name' => 'required',
                'blood_group' => 'required',
                'birthdate' => 'required',
                'personal_number' => 'required',
                'office_number' => 'nullable',
                'nid' => 'required|unique:users,nid,'.@$this->user->id,
                'address' => 'required',
                'email' => 'required|email|unique:users,email,'.@$this->user->id,
                'username' => 'required|unique:users,username,'.@$this->user->id,
                'password' => 'required',
                'gender' => 'required',
                'ewu_id_no' => 'required',
                'employment_status' => 'required',
            ];
        }


    }
}
