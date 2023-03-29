<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Admin\JobProfileRequest;
use App\Http\Resources\JobPortalUserCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\JobPortalUser;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\JobProfileRatingRequest;
use App\Models\UserRating;
use App\Models\Resume;
use App\Models\User;
use Auth;
use DB;


class JobProfileController extends Controller
{
   
    public function index()
    {
        if (request()->per_page) {
            // If more than 0
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = Resume::query()
                ->where($fieldName, 'LIKE', "%$keyword%")->orderBy('id', 'desc')
                ->with('userRating')
                ->paginate($perPage);
            return new JobPortalUserCollection($query);
        }
    }

    public function userRatingIndex($resume_id)
    {
        $data = UserRating::where('resume_id',$resume_id)->orderBy('id','desc')->get();
        // $data['note'] = $data[0]['note'];
        return response()->json($data);
    }


    public function store(JobProfileRequest $request)
    {
    
        try {
            
            DB::beginTransaction();
            $data = $request->all();
            $data['password'] = Hash::make($request->password);

            // switch ($request->employment_status) {
            //     case 'Admin':
            //         $data['role_id'] = 2;
            //         break;

            //     case 'Alumni':
            //         $data['role_id'] = 3;
            //         break;

            // // Commit the transaction
            // DB::commit();

            //     case 'Student':
            //         $data['role_id'] = 4;
            //         break;

            //     default:
            //         $data['role_id'] = 5;
            //         break;
            // }
            if ($request->auth_id){
                $user = User::query()->findOrFail($request->auth_id);
                $data['email'] = $user->email;
                $data['username'] = $user->username;
                $data['password'] = $user->password;
                $data['user_id'] = $user->id;
                $resume =  Resume::query()->create($data); //inserted data on Resume table
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Job Account created successfully',
                ],200);
            } else{
                $user = User::create($data);      //created auth user
                $data['user_id'] = $user->id;
                $resume =  Resume::create($data); //inserted data on Resume table
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'User created successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ],200);
            }


            } catch (\Exception $e) {
            
            // An error occured; cancel the transaction...
            
            DB::rollback();
            
            // and throw the error again.
            
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $e->getMessage()
            ],500);
            
            }
    }

    public function userRatingStore(JobProfileRatingRequest $request) {
        $UserRating = UserRating::where('resume_id',$request->resume_id)->get();
        if($UserRating){
            foreach($UserRating as $item){
                $item->delete();
            }
        }
        foreach($request->ratingFormDetails as $item) {
                $item['resume_id'] = $request->resume_id;
                $item['note'] = $request->note;
                $item = UserRating::create($item);
            }
        return response()->json('success');
    }


    public function userprofile($auth_id) {
        $jobProfileUser = User::where('id',$auth_id)->first();
        $jobProfileUser['resumes'] = $jobProfileUser->resume;
        return response()->json($jobProfileUser);
    }

    public function userlogin(request $request)
    {
       
        try {
            $validate = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
        ]);

            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validate->errors()
                ],401);
            }

            if (Auth::attempt($request->only(['email', 'password']))) {
                $user = User::where('email', $request->email)->first();
                $resume = Resume::where('user_id',$user->id)->first();

                return response()->json([
                    'status' => true,
                    'message' => 'User Logged in successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken,
                    'auth_id' => $user->id,
                    'resume_id' => @$resume->id,
                    'resume' => $resume,

                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record',
                    'errors' => $validate->errors()
                ], 401);
            }
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $th->getMessage()
            ],500);
        }
      
    }

    public function userlogout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }


    public function update(Request $request, $id)
    {
        try {
            $user = Resume::where('id',$id)->update($request->all());
            return response()->json([
                'type' => "success",
                'message' => 'Job Profile updated',
            ]);
        } catch (\Throwable $th){
            return response()->json([
                'type' => "error",
                'message' =>  $th->getMessage()
            ]);
        }

        // $user = Resume::where('id',$id)->update($request->all());
        // $data= [
        //     'type' => "error",
        //     'message' => "Current password Not match with old password",
        // ];
        // return response()->json('success');
    }

    public function accountUpdate(Request $request, $id)
    {
        $user = User::where('id',$id)->first();
        if($request->currentpassword && $request->newpassword) {
              #Match The Old Password
          if(!Hash::check($request->currentpassword, $user->password)) {
            $data= [
                'type' => "error",
                'message' => "Current password Not match with old password",
            ];
           }elseif($request->currentpassword == $request->newpassword) {
            $data= [
                'type' => "error",
                'message' => "Current password New password can't be same",
            ];
           }
           else{
                $request['password'] = Hash::make($request->newpassword); 
                $user = User::where('id',$id)->update(['password' => $request['password']]);

                $data= [
                    'type' => "success",
                    'message' => "password updated successfully",
                ];
            }
            return response()->json($data);
        }

        $data= [
            'type' => "error",
            'message' => "Input field can't be empty",
        ];
        return response()->json($data);
    }

    public function userRatingUpdate(Request $request, $id)
    {
        $userRating = UserRating::findOrFail($id);
        $userRating->update($request->all());
        return response()->json('success');
    }





    

    public function destroy($id)
    {
        
    }
}
