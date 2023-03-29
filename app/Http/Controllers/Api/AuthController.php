<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'bn_name' => 'required',
                    'mobile' => 'nullable|numeric|unique:users,mobile',
                    'phone_no' => 'nullable',
                    'nid' => 'nullable|unique:users,nid',
                    'address' => 'nullable',
                    'email' => 'required|email|unique:users,email',
                    'username' => 'required|unique:users,username',
                    'password' => 'required',
                ]);

            if ($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ],401);
            }

            // $user = User::create([
            //     'name' => $request->name,
            //     'bn_name' => $request->bn_name,
            //     'mobile' => $request->mobile,
            //     'phone_no' => $request->phone_no,
            //     'nid' => $request->nid,
            //     'employment_status' => $request->employment_status,
            //     'address' => $request->address,
            //     'email' => $request->email,
            //     'username' => $request->username,
            //     'password' => Hash::make($request->password),
            // ]);


            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            switch ($request->employment_status) {
                case 'Admin':
                    $data['role_id'] = 2;
                    break;

                case 'Alumni':
                    $data['role_id'] = 3;
                    break;

                case 'Student':
                    $data['role_id'] = 4;
                    break;

                default:
                    $data['role_id'] = 5;
                    break;
            }
            $user = User::create($data);








            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ],200);

        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $th->getMessage()
            ],500);
        }

    }

    protected function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required',
                ]);

            if ($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ],401);
            }
            if (!Auth::attempt($request->only(['email','password'])))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record',
                    'errors' => $validateUser->errors()
                ],401);
            }

            $user = User::where('email',$request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'auth_id' => $user->id
            ],200);

        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $th->getMessage()
            ],500);
        }
    }

}
