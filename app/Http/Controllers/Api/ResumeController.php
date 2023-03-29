<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\Address;

use Carbon\Carbon;

use App\Http\Requests\Admin\ResumeRequest;
use App\Interfaces\ResumeInterface;

class ResumeController extends Controller
{

    protected $resume;

    public function __construct(ResumeInterface $resume){
        $this->resume = $resume;
    }

    public function index()
    {
      return "hello";
    }

    public function userResume($user_id)
    {
        $personaldetails = Resume::where('user_id',$user_id)->with(['careerApplication','jobPreferredArea','resumeImage','specialization','employmenthistory','trainingSummary','professionalCertificaion','addresses','trainingSummary.training','jobPreferredArea.jobCategories','languages'])->first();
        if(isset($personaldetails->resumeImage)) {
            $personaldetails['resume_images'] = $personaldetails->resumeImage->source;
        }

        // dd($personaldetails['resume_image']);
        if(isset($personaldetails->addresses)) {
            foreach($personaldetails->addresses as $item) {
                $personaldetails['presentAddress'] = Address::where('user_id',$user_id)->where('type','present')->with(['division','district','thana'])->first();
                $personaldetails['permanentAddress'] = Address::where('user_id',$user_id)->where('type','permanent')->with(['division','district','thana'])->first();
            }
        }
        if( isset($personaldetails->specialization)) {
            $specializations = json_decode($personaldetails->specialization->skill_ids);
            $personaldetails['specializeSkills'] = Skill::whereIn('id',$specializations)->get();
        }
        return response()->json($personaldetails);
    }
  
    public function store(ResumeRequest $request)
    {
        $parameters = [
            'image_info' => [
                [
                    'type' => 'resume_image',
                    'images' => $request->resume_image,
                    'directory' => 'resumeImage',
                    'input_field' => 'resume_image',
                    'width' => '',
                    'height' => '',
                ],
            ],
            'file_info' => [
                [
                    'type' => 'resumeFile',
                    'files' => $request->resume_file,
                    'directory' => 'resumeFile',
                    'input_field' => 'resume_file',
                    'width' => '',
                    'height' => '',
                ],
            ],
        ];
        $resume = $this->resume->create($request, $parameters);
        return response()->json($resume);
    }

   
    public function update(ResumeRequest $request, Resume $resume)
    {
            $parameters = [
                'image_info' => [
                    [
                        'type' => 'resume_image',
                        'images' => $request->resume_image,
                        'directory' => 'resumeImage',
                        'input_field' => 'resume_image',
                        'width' => '',
                        'height' => '',
                    ],
                ],
                'file_info' => [
                    [
                        'type' => 'resumeFile',
                        'files' => $request->resume_file,
                        'directory' => 'resumeFile',
                        'input_field' => 'resume_file',
                        'width' => '',
                        'height' => '',
                    ],
                ],
            ];
        $resume = $this->resume->update($resume->id, $request, $parameters);
        return response()->json($resume);
    }

  
    public function destroy($id)
    {
        $personaldetails = Resume::where('id',$id)->delete();
        return response()->json('success');
    }
}
