<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobPostRequest;
use App\Interfaces\JobPostInterface;
use App\Models\Address;
use App\Models\JobPost;
use App\Models\User;
use App\Notifications\CreatorJobPostApproved;
use App\Notifications\NewJobNotify;
use Illuminate\Support\Collection;
use App\Notifications\NewJobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\JobPostCollection;


class JobPostController extends Controller
{
    protected $jobPost;

    public function __construct(JobPostInterface $jobPost)
    {
        $this->jobPost = $jobPost;
    }

    public function index()
    {
        if (request()->per_page) {
            // If more than 0
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = JobPost::query()
            ->with(['job_applications','address'])
                ->where($fieldName, 'LIKE', "%$keyword%")
                // ->where('is_approved', 'Yes')
                ->orderBy('id', 'desc')->paginate($perPage);
            return new JobPostCollection($query);
        } else {
          
        //     return response()->json(request()->input('prices', []));

        //     $jobposts = JobPost::withFilters(
        //         request()->input('prices', []),
        //         request()->input('employment_status', []),
             
        //     )->get();
        // return new JobPostCollection($jobposts);

        $prices =  request()->input('prices', []);
        $employment_status =  request()->input('employment_status', []);

        $query = JobPost::query()

         ->when(request()->input('prices', []), function($query) use($prices) {
            $query
            ->when(in_array(0, $prices), function ($query) {
                $query->whereBetween('min_salary', ['10000', '19999']);
                $query->whereBetween('max_salary', ['10000', '20000']);
            })
            ->when(in_array(1, $prices), function ($query) {
                $query->orWhereBetween('min_salary', ['20000', '29999']);
                $query->orWhereBetween('max_salary', ['20001', '30000']);
            })
            ->when(in_array(2, $prices), function ($query) {
                $query->orWhereBetween('min_salary', ['30000', '39999']);
                $query->orWhereBetween('max_salary', ['30000', '40000']);
            })
            ->when(in_array(3, $prices), function ($query) {
                $query->orWhereBetween('min_salary', ['40000', '49999']);
                $query->orWhereBetween('max_salary', ['40000', '50000']);
            });
            })

            ->when(request()->input('employment_status', []), function($query) use($employment_status) {
                $query
                ->when(in_array(0, $employment_status), function ($query) {
                 
                    $query->orWhereIn('employment_status', ['Full Time']);
                })
                ->when(in_array(1, $employment_status), function ($query) {
                    $query->orWhereIn('employment_status', ['Part Time']);
                })
                ->when(in_array(2, $employment_status), function ($query) {
                    $query->orWhereIn('employment_status', ['Contractual']);
                })
                ->when(in_array(3, $employment_status), function ($query) {
                    $query->orwhereIn('employment_status', ['Internship']);
                })

                ->when(in_array(3, $employment_status), function ($query) {
                    $query->orWhereIn('employment_status', ['Freelance']);
                });
                })->get();
        return response()->json($query);
        }
    }


    public function newJobPostsForAdmin()
    {
        if (request()->per_page) {
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = JobPost::query()
                ->where($fieldName, 'LIKE', "%$keyword%")
                ->where('is_approved', 'No')
                ->orderBy('id', 'desc')->paginate($perPage);
            return new JobPostCollection($query);
        }
    }

    public function deletedListIndex()
    {
        $jobPost = $this->jobPost->with(['address'])->onlyTrashed()->get();
        return response()->json($jobPost);
    }

    public function store(JobPostRequest $request)
    {
        $data = $request;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'company_logo',
                    'images' => $data->company_logo,
                    'directory' => 'company_logo',
                    'input_field' => 'company_logo',
                    'width' => '',
                    'height' => '',
                ],
            ],
        ];
            DB::beginTransaction();

            try {
                $jobPost = $this->jobPost->create($data, $parameters);

                $data['job_post_id'] = $jobPost->id;
                Address::create($data->all());
                DB::commit();
                // all good
            } catch (\Exception $e) {
                DB::rollback();
                dd($e->getMessage());
                // something went wrong
            }


        //mail-notification to admin for approval
        // $admins = User::query()->where('is_admin','Yes')->get();
        // Notification::send($admins, new NewJobPost($jobPost));

        return response()->json([
            'data' => $jobPost,
            'success' => 'Job Post Created Successfully',
        ], 200);
    }

    public function show(JobPost $job_post)
    {
        $jobPost = $this->jobPost->findOrFail($job_post->id);
        $jobPost['company_logo'] = $jobPost->websiteLogo->source;
        $jobPost['division_id'] = $jobPost->address->division_id;
        $jobPost['district_id'] = $jobPost->address->district_id;
        $jobPost['thana_id'] = $jobPost->address->thana_id;
        return response()->json($jobPost);
    }

    // public function edit($id)
    // {
    //     $jobPost = $this->jobPost->findOrFail($job_post->id);
    //     $jobPost['webs_logo'] = $jobPost->websiteLogo->source;
    //     return response()->json($jobPost);
    // }

    public function update(JobPostRequest $request, JobPost $job_post)
    {
        $data = $request;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'company_logo',
                    'images' => $data->company_logo,
                    'directory' => 'company_logo',
                    'input_field' => 'company_logo',
                    'width' => '',
                    'height' => '',
                ],
            ],
        ];
            DB::beginTransaction();
            try {
                $jobPost = $this->jobPost->update($job_post->id, $data, $parameters);
                $addressData['job_post_id'] = $jobPost->id;
                $addressData['division_id'] = $job_post->address->division_id;
                $addressData['district_id'] = $job_post->address->district_id;
                $addressData['thana_id'] =   $job_post->address->thana_id;
                Address::where('id',$job_post->address->id)->update($addressData);
                DB::commit();
                // all good
            } catch (\Exception $e) {
                DB::rollback();
                dd($e->getMessage());
                // something went wrong
            }


        //mail-notification to admin for approval
        // $admins = User::query()->where('is_admin','Yes')->get();
        // Notification::send($admins, new NewJobPost($jobPost));

        return response()->json([
            'data' => $jobPost,
            'success' => 'Job Post updated Successfully',
        ], 200);
    }

    public function destroy(JobPost $job_post)
    {
        DB::beginTransaction();
        try {
            $this->jobPost->delete($job_post->id);
            // $jobPost->address->delete();
            DB::commit();
            return response()->json([
                'message' => trans('jobPost.deleted'),
            ], 200);
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'error', $e->getMessage()
            ]);
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        $this->jobPost->restore($id);
        $address = Address::onlyTrashed()->where('job_post_id', $id)->first();
        if ($address != null) {
            $address->restore();
        }
        DB::commit();
        return response()->json([
            'message' => trans('jobPost.restored'),
        ], 200);
        DB::rollBack();
        return response()->json([
            'error', $e->getMessage()
        ]);
    }

    public function forceDelete($id)
    {
        $this->jobPost->forceDelete($id);

        return response()->json([
            'message' => trans('jobPost.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->jobPost->status($request->id);

        return response()->json([
            'message' => trans('jobPost.status_updated'),
        ], 200);
    }

    //job post approval
    public function jobPostApproval($id)
    {
        JobPost::query()->findOrFail($id)->update(["is_approved" => "Yes"]);

        return response()->json([
            "success" => trans('jobPost.approved')
        ],200);

        /*$jobPost = JobPost::query()->findOrFail($id);
        if ($jobPost->is_approved == "No"){
            $jobPost->update(["is_approved" => "Yes"]);

            // mail-notification for job post creator after approval
            $jobPost->user->notify(new CreatorJobPostApproved($jobPost));

            //start mail-notification for related job-seekers
            $needToNotifyUser = Collection::empty();
            $seekers = User::query()->where('employment_status','Job-Seeker')->get();

            foreach ($jobPost->department_ids as $department_id) {
                foreach ($seekers as $seeker){
                    if ($seeker->alumni_id){
                        if ($seeker->alumni->department_id == $department_id)
                            $needToNotifyUser[$seeker->id] = $seeker;
                    }elseif($seeker->student_id){
                        if ($seeker->student->department_id == $department_id)
                            $needToNotifyUser[$seeker->id] = $seeker;
                    }
                }
            }

            foreach ($needToNotifyUser as $user){
                Notification::route('mail',$user->email)->notify(new NewJobNotify($jobPost));
            }

            //end mail-notification for related job-seekers


            return response()->json([
                "success" => trans('jobPost.approved')
            ],200);
        } else{

            return response()->json([
                "info" => trans('jobPost.already_approved')
            ],208);
        }*/
    }

    public function singlecategory($id)
    {

        $prices =  request()->input('prices', []);
        $employment_status =  request()->input('employment_status', []);

        $query = JobPost::query()
        ->where('job_category_id',$id)
        
      
         ->when(request()->input('prices', []), function($query) use($prices,$id) {
            $query
            ->when(in_array(0, $prices), function ($query,$id) {
                $query->whereBetween('min_salary', ['10000', '19999']);
                $query->whereBetween('max_salary', ['10000', '20000']);
            })
            ->when(in_array(1, $prices), function ($query,$id) {
                
                $query->orWhereBetween('min_salary', ['20000', '29999']);
                $query->orWhereBetween('max_salary', ['20001', '30000']);
            })
            ->when(in_array(2, $prices), function ($query,$id) {
                $query->orWhereBetween('min_salary', ['30000', '39999']);
                $query->orWhereBetween('max_salary', ['30001', '40000']);
            });
            // ->when(in_array(3, $prices), function ($query,$id) {
            //     $query->orWhereBetween('min_salary', ['30001', '4']);
            //     $query->orWhereBetween('max_salary', ['40000', '50000']);
            // });
            })
            // ->when(request()->input('prices', []), function($query) use($prices,$id) {
            //     $query
            //     ->when(in_array(0, $prices), function ($query,$id) {
            //         $query->whereBetween('min_salary', ['10000', '19999'])->where('job_category_id',$id);
            //         $query->whereBetween('max_salary', ['10000', '20000'])->where('job_category_id',$id);
            //     })
            //     ->when(in_array(1, $prices), function ($query,$id) {
            //         $query->orWhereBetween('min_salary', ['20000', '29999'])->where('job_category_id',$id);
            //         $query->orWhereBetween('max_salary', ['20001', '30000'])->where('job_category_id',$id);
            //     })
            //     ->when(in_array(2, $prices), function ($query,$id) {
            //         $query->orWhereBetween('min_salary', ['30000', '39999'])->where('job_category_id',$id);
            //         $query->orWhereBetween('max_salary', ['30000', '40000'])->where('job_category_id',$id);
            //     })
            //     ->when(in_array(3, $prices), function ($query,$id) {
            //         $query->orWhereBetween('min_salary', ['40000', '49999'])->where('job_category_id',$id);
            //         $query->orWhereBetween('max_salary', ['40000', '50000'])->where('job_category_id',$id);
            //     });
            //     })

            // ->when(request()->input('employment_status', []), function($query) use($employment_status,$id) {
            //     $query
            //     ->when(in_array(0, $employment_status), function ($query,$id) {
                 
            //         $query->orWhereIn('employment_status', ['Full Time'])->where('job_category_id',$id);
            //     })
            //     ->when(in_array(1, $employment_status), function ($query,$id) {
            //         $query->orWhereIn('employment_status', ['Part Time'])->where('job_category_id',$id);
            //     })
            //     ->when(in_array(2, $employment_status), function ($query,$id) {
            //         $query->orWhereIn('employment_status', ['Contractual'])->where('job_category_id',$id);
            //     })
            //     ->when(in_array(3, $employment_status), function ($query,$id) {
            //         $query->orwhereIn('employment_status', ['Internship'])->where('job_category_id',$id);
            //     })

            //     ->when(in_array(3, $employment_status), function ($query,$id) {
            //         $query->orWhereIn('employment_status', ['Freelance'])->where('job_category_id',$id);
            //     });
            //     })
                ->get();

        return response()->json($query);
    }


    public function recentjobs()
    {
            // If more than 0
            $perPage = request()->per_page;

            $query = JobPost::query()
                ->orderBy('id', 'asc')
                ->paginate($perPage);

            return new JobPostCollection($query);
      
    }
    public function jobsearch(Request $request)
    {
        $jobpost = JobPost::where("job_title","LIKE","%".$request->job_title."%")
        ->orwhere("company_name","LIKE","%".$request->job_title."%")->get();
        return response()->json($jobpost);
    }

    public function homesearchjob($searchkeyword)
    {
        $jobpost = JobPost::where("job_title","LIKE","%".$searchkeyword."%")
        ->orwhere("company_name","LIKE","%".$searchkeyword."%")->get();
        return response()->json($jobpost);
    }


    public function salaryrange() {
        $salaries = [
            10000, 20000, 30000, 40000
        ];
        return response()->json($salaries);
    }

    public function selectedtype(Request $request) {
        return response()->json($request->type);
    }
    
}
