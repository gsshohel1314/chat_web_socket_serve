<?php

namespace App\Http\Controllers\Api;

use App\Mail\NewsletterMail;
use App\Models\Admin;
use random;
use App\Models\User;
use App\Models\Skill;
use App\Models\Alumni;
use App\Models\Country;
use App\Models\District;
use App\Models\Department;
use App\Models\Achievement;
use App\Models\AlumniSkill;
use App\Models\Endorsement;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\AlumnisExport;
use App\Imports\AlumnisImport;
use App\Interfaces\UserInterface;
use App\Mail\AlumniInvitationMail;
use Illuminate\Support\Facades\DB;
use App\Interfaces\AlumniInterface;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;
use App\Http\Resources\AlumniResource;
use App\Notifications\AlumniInvitation;
use App\Http\Resources\AlumniCollection;
use App\Notifications\ProfileCompletion;
use App\Http\Requests\Admin\AlumniRequest;
use Illuminate\Support\Facades\Notification;
use Multicaret\Acquaintances\Models\Friendship;
use App\Http\Resources\ProfileCompletionResource;

class AlumniController extends Controller
{
    protected $user;
    protected $alumni;

    public function __construct(UserInterface $user, AlumniInterface $alumni)
    {
        $this->user = $user;
        $this->alumni = $alumni;
    }

    public function index()
    {
        // $alumnis = $this->alumni->with(['user', 'addresses', 'alumniSkills', 'alumniSkills.skill'])->get();

        // return response()->json($alumnis);

        if (!empty(request()->all())) {
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = Alumni::query()
            ->where($fieldName, 'LIKE', "%$keyword%")
            ->orderBy('id', 'desc')
            ->paginate($perPage);

            return new AlumniCollection($query);
        } else {
            $query = $this->alumni->with(['country', 'division', 'district', 'department', 'achievements', 'skills', 'experiences', 'educations'])->get();

            return new AlumniCollection($query);
        }

    }

    public function search(){

        $keyword = request()->globalSearch;
        if ($keyword != null){
            $query = Alumni::query()->with('alumni','backgroundImage','department')
                ->where('first_name', 'LIKE', "%$keyword%")
                ->orWhere('middle_name', 'LIKE', "%$keyword%")
                ->orWhere('last_name', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%")
                ->orWhere('passing_year', 'LIKE', "%$keyword%")
                ->orWhere('organization', 'LIKE', "%$keyword%")
                ->orWhereHas('department',function ($dep)use ($keyword){
                    $dep->where('title','LIKE', "%$keyword%");
                })
                ->orderBy('id', 'desc')
                ->get();
        }

        return response()->json([
            'data' => @$query ? $query : ''
        ],200);
    }

    public function deletedListIndex()
    {
        $alumnis = $this->alumni->onlyTrashed();
        return response()->json($alumnis);
    }

    public function allAlumnis() {
        return $this->alumni->query()->pluck('id');
    }

    public function store(AlumniRequest $request)
     {
        try {
            DB::beginTransaction();
            $data = $request;

            // Start user create
            $data['name'] = $data->first_name . ' ' . $data->middle_name . ' ' . $data->last_name;
            $data['phone'] = $data->personal_contact_number;
            $data['employment_status'] = 'Alumni';
            $data['password'] = Hash::make($request->password);
            $user = $this->user->create($data);
            // End user create

            $data['user_id'] = $user->id;
            $parameters = [
                'image_info' => [
                    [
                        'type' => 'alumni',
                        'images' => $data->image,
                        'directory' => 'alumnis',
                        'input_field' => 'image',
                        'width' => '',
                        'height' => '',
                    ],
                ],
            ];
            $alumni = $this->alumni->create($data, $parameters);
            DB::commit();

            return new AlumniResource($alumni);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error', $e->getMessage()
            ]);
        }


        // try {
        //     DB::beginTransaction();
        //     $data = $request;

        //     $experiencesData = [
        //         [
        //             "title" => $data->occupation,
        //             "company_name" => $data->organization_details,
        //             "designation_department" => $data->designation_department,
        //             "start_date" => $data->doj,
        //             "is_current" => 'Yes',
        //             "user_type" => 'alumni',
        //         ]
        //     ];

        //     $data['password'] = Hash::make($request->password);

        //     $parameters = [
        //         'image_info' => [
        //             [
        //                 'type' => 'alumni',
        //                 'images' => $data->image,
        //                 'directory' => 'alumnis',
        //                 'input_field' => 'image',
        //                 'width' => '',
        //                 'height' => '',
        //             ],
        //         ],

        //         'create_many' => [
        //             [
        //                 'relation' => 'experiences',
        //                 'data' => $experiencesData
        //             ],
        //         ],
        //     ];
        //     $alumni = $this->alumni->create($data, $parameters);

        //     DB::commit();

        //     return new AlumniResource($alumni);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'error', $e->getMessage()
        //     ]);
        // }
    }

    public function show(Alumni $alumnus)
    {
        // $alumni = $this->alumni->findOrFail($alumnus->id);
        // $department = Department::where('id', $alumni->department_id)->first();
        // $country = Country::where('id', $alumni->country_id)->first();
        // $district = District::where('id', $alumni->district_id)->first();

        // $skills = $alumni->skills;
        // foreach ($skills as $key => $skill) {
        //     $endorsement_count = Endorsement::where('user_id', $alumni->id)->where('activity_type', get_class($skill))->where('activity_id', $skill->id)->count();
        //     $skill['endorsement_count'] = $endorsement_count;
        //     $endorsers = Endorsement::with('user', 'user.alumni')->where('user_id', $alumni->id)->where('activity_type', get_class($skill))->where('activity_id', $skill->id)->get();
        //     $skill['endorsers'] = $endorsers;
        // }


        // return response()->json([
        //     'alumni' => $alumni,
        //     'image_url' => $alumni->alumni ? $alumni->alumni->source : null,
        //     'background_image_url' => $alumni->backgroundImage ? $alumni->backgroundImage->source : null,
        //     'department' => $department,
        //     'achievements' => $alumni->achievements,
        //     'skills' => $skills,
        //     'country' => $country,
        //     'district' => $district,
        // ]);

        $alumni = $this->alumni->findOrFail($alumnus->id);
        return new AlumniResource($alumni);
    }

    public function edit($id)
    {
        $alumni = $this->alumni->findOrFail($id);
        return response()->json($alumni);
    }

    public function update(AlumniRequest $request, Alumni $alumnus)
    {
        try {
            DB::beginTransaction();
            $data = $request;

            // alumni update from admin panel
            if($data->valueFrom == 'alumni_update') {
                // Start user update
                $data['name'] = $data->first_name . ' ' . $data->middle_name . ' ' . $data->last_name;
                $data['phone'] = $data->personal_contact_number;
                if ($data->password != null) {
                    if (!Hash::check($data->current_password, $data->old_password)) {
                        $request['errorMsgForCurrentPassword'] = "Current password dosen't match our records";
                        $data['password'] = $request->old_password;
                    } else {
                        $data['password'] = Hash::make($request->password);
                    }
                } else {
                    $data['password'] = $request->old_password;
                }

                $this->user->update($alumnus->user_id, $data);
                // End user update

                $parameters = [
                    'image_info' => [
                        [
                            'type' => 'alumni',
                            'images' => $data->image,
                            'directory' => 'alumnis',
                            'input_field' => 'image',
                            'width' => '',
                            'height' => '',
                        ],
                    ],
                ];
                $alumni = $this->alumni->update($alumnus->id, $data, $parameters);
            }

            // alumni background image update from alumni profile
            if ($data->valueFrom == 'background_image') {
                $parameters = [
                    'image_info' => [
                        [
                            'type' => 'alumni-backgroud',
                            'images' => $data->background_image,
                            'directory' => 'alumnis/background',
                            'input_field' => 'image',
                            'width' => '',
                            'height' => '',
                        ],
                    ],
                ];
                $alumni = $this->alumni->update($alumnus->id, $data, $parameters);
            }

            // alumni profile image update from alumni profile
            if ($data->valueFrom == 'profile_image') {
                $parameters = [
                    'image_info' => [
                        [
                            'type' => 'alumni',
                            'images' => $data->profile_image,
                            'directory' => 'alumnis',
                            'input_field' => 'image',
                            'width' => '',
                            'height' => '',
                        ],
                    ],
                ];
                $alumni = $this->alumni->update($alumnus->id, $data, $parameters);
            }

            // alumni skill info update from alumni profile
            if ($data->valueFrom == 'skill_info') {
                $alumni = $this->alumni->update($alumnus->id, $data);
                $alumni->skills()->attach($request->skill_ids);
            }

            // alumni achievement info update from alumni profile
            if ($data->valueFrom == 'achievement_info') {
                $alumni = $this->alumni->update($alumnus->id, $data);
                $alumni->achievements()->attach($request->achievement_ids);
            }

            // alumni username & email info update from alumni profile
            if ($data->valueFrom == 'username_email_info') {
                $this->user->update($alumnus->user_id, $data);
            }

            // alumni password info update from alumni profile
            if ($data->valueFrom == 'password_info') {
                if (Hash::check($data->current_password, $data->old_password) == true) {
                    $data['current_password'] = Hash::make($data->current_password);
                    $data['password'] = Hash::make($data->new_password);
                    $data['password_confirm'] = Hash::make($data->password_confirm);

                    $this->user->update($alumnus->user_id, $data);
                } else{
                    $request['errorMsgForCurrentPassword'] = "Current password dosen't match our records";
                }
            }

            // alumni rating info from admin panel
            if ($data->valueFrom == 'rating_info') {
                $alumni = $this->alumni->update($alumnus->id, $data);
            }

            $alumni = $this->alumni->update($alumnus->id, $data);

            DB::commit();

            $request['update'] = 'update';

            return new AlumniResource($alumni);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error', $e->getMessage()
            ]);
        }
    }

    public function destroy(Alumni $alumnus)
    {
        if (request()->valueFrom == 'skill_info') {
            $alumnus->skills()->detach(request()->skill_id);

            return response()->json([
                'message' => trans('alumni.skill_deleted'),
            ], 200);
        }elseif (request()->valueFrom == 'achievement_info') {
            $alumnus->achievements()->detach(request()->achievement_id);

            return response()->json([
                'message' => trans('alumni.achievement_deleted'),
            ], 200);
        }else{
            $this->alumni->delete($alumnus->id);

            return response()->json([
                'message' => trans('alumni.soft_delete'),
            ], 200);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $this->alumni->restore($id);
            $user = User::onlyTrashed()->where('alumni_id', $id)->first();
            if ($user != null) {
                $user->restore();
            }
            DB::commit();

            return response()->json([
                'message' => trans('alumni.alumni_restored_successfully'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error', $e->getMessage()
            ]);
        }
    }

    public function forceDelete($id)
    {
        $this->alumni->forceDelete($id);

        return response()->json([
            'message' => trans('alumni.alumni_deleted_permanently'),
        ], 200);
    }

    public function massDestroy($alumnis){
        $alumnisArray = explode(',', $alumnis);
        Alumni::whereKey($alumnisArray)->delete();

        return response()->noContent();
    }

    public function status(Request $request)
    {
        $this->alumni->status($request->id);

        return response()->json([
            'message' => trans('alumni.alumni_status_updated_successfully'),
        ], 200);
    }

    public function alumniProfileCompletionPercentage($id) {
        $alumni = Alumni::with('experiences', 'educations', 'skills', 'achievements')->findOrFail($id);
        // dd($alumni);
        $sum = 0;
        if($alumni->alumni) {
            $sum = $sum + 10;
        }
        if ($alumni->backgroundImage) {
            $sum = $sum + 10;
        }
        // basic info
        if ($alumni->ewu_id_no) {
            $sum = $sum + 2;
        }
        if ($alumni->first_name || $alumni->middle_name || $alumni->last_name) {
            $sum = $sum + 2;
        }
        if ($alumni->dob) {
            $sum = $sum + 2;
        }
        if ($alumni->blood_group) {
            $sum = $sum + 2;
        }
        // organization
        if ($alumni->organization) {
            $sum = $sum + 2;
        }
        if ($alumni->designation_department) {
            $sum = $sum + 2;
        }
        // location
        if ($alumni->country_id) {
            $sum = $sum + 2;
        }
        if ($alumni->district_id) {
            $sum = $sum + 2;
        }
        // Contact Info
        if ($alumni->contact_number) {
            $sum = $sum + 2;
        }
        if ($alumni->personal_email || $alumni->university_email || $alumni->company_email) {
            $sum = $sum + 2;
        }
        if ($alumni->facebook_profile_link) {
            $sum = $sum + 2;
        }
        if ($alumni->linkedin_profile_link) {
            $sum = $sum + 2;
        }
        // about
        if ($alumni->about) {
            $sum = $sum + 10;
        }
        // experience
        if (sizeof($alumni->experiences)) {
            $sum = $sum + 10;
        }
        // education
        if (sizeof($alumni->educations)) {
            $sum = $sum + 10;
        }
        // skills
        if (sizeof($alumni->skills)) {
            $sum = $sum + 10;
        }
        // achievement
        if (sizeof($alumni->achievements)) {
            $sum = $sum + 10;
        }

        return response()->json([
            'percentage' => $sum,
        ], 200);
    }

    public function import()
    {
        Excel::import(new AlumnisImport(),request()->file('file'));

        return response()->json([
            'message' => 'alumni created successfully',
        ], 200);
    }

    public function exportXLS($alumniIds)
    {
        $alumnisArray = explode(',', $alumniIds);
        return Excel::download(new AlumnisExport($alumnisArray), 'alumnis.xlsx');
    }

    public function inviteOthers()
    {
        $sender = Alumni::query()->findOrFail(request()->auth_id);
        $sender['email'] = request()->email;

        Mail::to(request()->email)->send(new AlumniInvitationMail($sender));

        return response()->json([
           'data' =>'success'
        ]);
    }

    public function profileCompletionNotification($receiverAlumniId) {
        $alumni = Alumni::findOrFail($receiverAlumniId);
        $alumni->notify(new ProfileCompletion(request()->sender_alumni_id, request()->profile_completion_percentage_amount));

        return response()->noContent();
    }

    public function getUnreadProfileCompletionNotification() {
        $alumni = Alumni::findOrFail(request()->auth_id);

        return ProfileCompletionResource::collection($alumni->unreadNotifications);
    }

    public function readProfileCompletionNotification() {
        $alumni = Alumni::findOrFail(request()->alumni_id);
        // $alumni->notifications->markAsRead();

        $userUnreadNotification = $alumni->unreadNotifications
        ->where('id', request()->notification_id)
        ->first();

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }

        return response()->json('success');
    }


    // public function getSuggestionAlumnis() {
    //     // $data = Friendship::where('sender_id', request()->auth_id)->where('status', 'pending')->pluck('recipient_id')->toArray();
    //     $sendFriendRequests = Friendship::where('sender_id', request()->auth_id)->pluck('recipient_id')->toArray();
    //     $receiveFriendRequests = Friendship::where('recipient_id', request()->auth_id)->pluck('sender_id')->toArray();
    //     $data = array_merge($sendFriendRequests, $receiveFriendRequests);
    //     array_push($data, (int)request()->auth_id);
    //     // dd($data);
    //     $query = $this->alumni->with(['country', 'division', 'district', 'department', 'achievements', 'skills', 'experiences', 'educations'])->whereNotIn('id', $data)->get();

    //     return new AlumniCollection($query);
    // }

    // public function sendFriendRequest($recipientAlumniId) {
    //     try {
    //         $alumnus = Alumni::findOrFail(request()->auth_id);
    //         $recipient = Alumni::findOrFail($recipientAlumniId);
    //         $data = $alumnus->befriend($recipient);
    //         // $recipient->update([
    //         //     'friendship_status' =>  $data->status,
    //         // ]);

    //         return response()->json([
    //             'data' => $data,
    //             'message' => 'Connection Request Send',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function cancelFriendRequest($recipientAlumniId)
    // {
    //     try {
    //         $alumnus = Alumni::findOrFail(request()->auth_id);
    //         $recipient = Alumni::findOrFail($recipientAlumniId);
    //         $data = $alumnus->unfriend($recipient);
    //         // $recipient->update([
    //         //     'friendship_status' =>  NULL,
    //         // ]);

    //         return response()->json([
    //             'data' => $data,
    //             'message' => 'Cancel Request',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function acceptFriendRequest($senderAlumniId) {
    //     try {
    //         $alumnus = Alumni::findOrFail(request()->auth_id);
    //         $sender = Alumni::findOrFail($senderAlumniId);
    //         $data = $alumnus->acceptFriendRequest($sender);

    //         return response()->json([
    //             'data' => $data,
    //             'message' => 'Friend Request Accept',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function denyFriendRequest($senderAlumniId)
    // {
    //     try {
    //         $alumnus = Alumni::findOrFail(request()->auth_id);
    //         $sender = Alumni::findOrFail($senderAlumniId);
    //         // $data = $alumnus->denyFriendRequest($sender);
    //         $data = $alumnus->unfriend($sender);

    //         return response()->json([
    //             'data' => $data,
    //             'message' => 'Deny Request',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function unriend($senderAlumniId)
    // {
    //     // dd($senderAlumniId);
    //     try {
    //         $alumnus = Alumni::findOrFail(request()->auth_id);
    //         $sender = Alumni::findOrFail($senderAlumniId);
    //         $data = $alumnus->unfriend($sender);

    //         return response()->json([
    //             'data' => $data,
    //             'message' => 'Successfully unfriend',
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function getConnectionFriends() {
    //     try {
    //         $alumni = Alumni::findOrFail(request()->auth_id);
    //         $data = $alumni->getAcceptedFriendships();
    //         $sender_id = $data->pluck('sender_id')->toArray();
    //         $recipient_id = $data->pluck('recipient_id')->toArray();

    //         $merge_array = array_merge($sender_id, $recipient_id);
    //         $data = array_diff($merge_array, [request()->auth_id]);

    //         $query = $this->alumni->with(['country', 'division', 'district', 'department', 'achievements', 'skills', 'experiences', 'educations'])->whereIn('id', $data)->get();

    //         return new AlumniCollection($query);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function getPendingFriendRequests() {
    //     try {
    //         $data = Friendship::where('sender_id', request()->auth_id)->where('status', 'pending')->pluck('recipient_id')->toArray();
    //         $query = $this->alumni->with(['country', 'division', 'district', 'department', 'achievements', 'skills', 'experiences', 'educations'])->whereIn('id', $data)->get();

    //         return new AlumniCollection($query);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function getInvitationFriendRequests() {
    //     try {
    //         $alumni = Alumni::find(request()->auth_id);
    //         $data = $alumni->getFriendRequests();
    //         $sender_id = $data->pluck('sender_id')->toArray();
    //         $query = $this->alumni->with(['country', 'division', 'district', 'department', 'achievements', 'skills', 'experiences', 'educations'])->whereIn('id', $sender_id)->get();

    //         return new AlumniCollection($query);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }



    // public function totalFriendsCount () {
    //     try {
    //         $alumni = Alumni::findOrFail(request()->auth_id);
    //         $data = $alumni->getFriendsCount();
    //         // dd($data);

    //         return response()->json([
    //             'data' => $data,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function totalPendingFriendRequestCount () {
    //     try {
    //         $data = Friendship::where('sender_id', request()->auth_id)->where('status', 'pending')->count();

    //         return response()->json([
    //             'data' => $data,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }

    // public function totalInvitationCount() {
    //     try {
    //         $alumni = Alumni::findOrFail(request()->auth_id);
    //         $data = $alumni->getFriendRequests();

    //         return response()->json([
    //             'data' => $data->count(),
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error', $e->getMessage()
    //         ]);
    //     }
    // }
}
