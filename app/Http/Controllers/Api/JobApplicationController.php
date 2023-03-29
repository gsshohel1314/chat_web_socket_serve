<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendApplicantResume;
use Illuminate\Http\Request;

use App\Http\Resources\JobApplicationCollection;
use App\Interfaces\JobApplicationInterface;
use App\Models\JobApplication;
use App\Models\UserRating;
use App\Models\Resume;
use Illuminate\Support\Facades\Mail;
use Image;
use Illuminate\Mail\Attachment;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;


class JobApplicationController extends Controller
{
    protected $jobapplication;

    public function __construct(JobApplicationInterface $jobapplication)
    {
        $this->jobapplication = $jobapplication;
    }
    public function index()
    {
        if (request()->per_page) {
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;
            $query = JobApplication::query()
            ->with('resume')
            ->paginate($perPage);
            // ->where($fieldName, 'LIKE', "%$keyword%")->orderBy('id', 'desc')->paginate($perPage);

            return new JobApplicationCollection($query);
        }
    }

    public function userJobApplication($resume_id) {
        // dd(request()->selectedDate['from_date']);
        // dd(request()->keyword);
        if (request()->per_page) {
            $perPage = request()->per_page;
            $from_date = request()->selectedDate['from_date'];
            $to_date = request()->selectedDate['to_date'];
            $keyword = request()->keyword;

            $query = JobApplication::query()
            ->where('resume_id',$resume_id)
            ->with('resume')
            ->with('job_post')

            ->when(request()->selectedDate['from_date'] != null && request()->selectedDate['to_date'] != null, function($query) use($from_date,$to_date) {
                $query->whereBetween('applyed_date', [$from_date, $to_date]);
            })
            ->whereHas('job_post', function($query) use($keyword)
            {
                $query->where('company_name', 'LIKE', "%$keyword%");
                $query->orWhere('company_address', "LIKE", "%$keyword%");
                $query->orWhere('job_title', "LIKE", "%$keyword%");
            })
            ->where('withdraw_status',false)
            ->paginate($perPage);
            return new JobApplicationCollection($query);
        }
    }

    public function userJobWithdrawApplicationList($resume_id) {
  
        if (request()->per_page) {
            $perPage = request()->per_page;
            $from_date = request()->selectedDate['from_date'];
            $to_date = request()->selectedDate['to_date'];
            $keyword = request()->keyword;

            $query = JobApplication::query()
            ->where('resume_id',$resume_id)
            ->with('resume')
            ->with('job_post')

            ->when(request()->selectedDate['from_date'] != null && request()->selectedDate['to_date'] != null, function($query) use($from_date,$to_date) {
                $query->whereBetween('applyed_date', [$from_date, $to_date]);
            })
            ->whereHas('job_post', function($query) use($keyword)
            {
                $query->where('company_name', 'LIKE', "%$keyword%");
                $query->orWhere('company_address', "LIKE", "%$keyword%");
                $query->orWhere('job_title', "LIKE", "%$keyword%");
            })
            ->where('withdraw_status',true)
            ->paginate($perPage);
            return new JobApplicationCollection($query);
        }
    }

    public function jobApplicationWithdraw(Request $request) {
        $request->validate([
            'withdraw_reson' =>'required'
        ]);
        $jobApplication = JobApplication::where('id',$request->id)->first();
        $jobApplication->update([
            'withdraw_status'=>true,
            'withdraw_reson'=>$request->withdraw_reson,
        ]);
        return response()->json('success');
    }

    public function jobApplicationWithdrawCancle($id) {
        JobApplication::where('id',$id)->update([
            'withdraw_status'=>false,
        ]);
        return response()->json('success');
    }

    

    public function allShortlist($jobId)
    {
        $perPage = request()->per_page;
        $query = JobApplication::query()->with('resume')
        ->where('withdraw_status',false)
        ->where('job_post_id',$jobId)
        ->where('job_status','Interviewed')->orderBy('id', 'desc')->paginate($perPage);
        return new JobApplicationCollection($query);
    }

    public function jobApplications($jobId)
    {
        $perPage = request()->per_page;
        $query = JobApplication::query()
        ->with('resume')
        ->where('withdraw_status',false)
        ->where('job_post_id',$jobId)
            ->where('job_status','New')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        return new JobApplicationCollection($query);
    }


    public function withdrawApplication($jobId)
    {
        $perPage = request()->per_page;
        $query = JobApplication::query()
        ->with('resume')
        ->where('withdraw_status',true)
        ->where('job_post_id',$jobId)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        return new JobApplicationCollection($query);
    }


    public function shortlist($id)
    {
        $jobApplication = JobApplication::query()->findOrFail($id)->update(['job_status'=>'Interviewed']);

        return response()->json($jobApplication);
    }

    public function removeShortlist($id)
    {
        $jobApplication = JobApplication::query()->findOrFail($id)->update(['job_status'=>'New']);

        return response()->json($jobApplication);
    }

    public function store(Request $request)
    {
            $jobApplication = JobApplication::where('job_post_id', $request->job_post_id)->where('resume_id', $request->resume_id)->exists();
            $authResume = Resume::where('id', $request->resume_id)->exists();

            if(!$authResume) {
                $response = ['status' => 'error', 'message' => 'You have no account in Job portal'];
            }
            elseif($jobApplication) {
                $response = ['status' => 'error', 'message' => 'You Already Applyed for this job'];
            }else {
                // $request['applyed_date'] = Carbon::now()->format('Y-m-d');
                $request['applyed_date'] = Carbon::now()->format('Y-m-d');

                // Carbon::tomorrow()->format('l Y m d')
                $jobApplication = JobApplication::create($request->all());
                $response = ['status' => 'success', 'message' => 'You Applyed for this job successfully',200];
                }
        return response()->json($response);

     
        // $data = $request;
        // $request->validate([
        //     'full_name' => 'required',
        //     'email' => 'required|email|unique:apply_jobs,email',
        //     'cover_letter' => 'required',
        //     'file' => 'required|mimes:pdf|max:2048',

        // ]);

        // $parameters = [
        //     'file_info' => [
        //         [
        //             'type' => 'job_application',
        //             'files' => $data->file,
        //             'directory' => 'jobapplication_pdf',
        //             'input_field' => 'file',
                   
        //         ],
        //     ],
        // ];

        // try {

        //     $jobapplication = $this->jobapplication->create($request, $parameters);
        //     return response()->json($jobapplication);
          
        //   } catch (\Exception $e) {
          
        //      dd($e->getMessage());
        //   }
    
    }




    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $jobapplication = $this->jobapplication->findOrFail($id);
       
        $this->jobapplication->delete($jobapplication->id);
        return response()->json([
            'message' => trans('Interest deleted successfully'),
        ], 200);
    }

    public function download($id)
    {
        $application = JobApplication::findOrFail($id);
        $file = $application->file;
        $file= public_path(). "/uploads/attachment/jobapplications/".$file;
        return response()->download($file,'filename.pdf');

    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'sendTo' => 'required|email',
            'subject' => 'required|string',
            'body' => 'required',
        ]);
        $data = $request;

        $files = [];
        foreach ($data['applicantIds'] as $id){
            $application = JobApplication::query()->findOrFail($id);
            $file = $application->file;
            $file = URL::to("/uploads/attachment/jobapplications/".$file);
            $files[] = $file;
        }

        $data['resumes'] = $files;
        Mail::to($data['sendTo'])->send(new SendApplicantResume($data));

        return response()->json(['message' => 'Email sent successfully']);
    }


}