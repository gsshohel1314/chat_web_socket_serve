<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\NewsletterMailInterface;
use App\Models\CreateMailList;
use App\Models\NewsletterMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Excel;

class NewsletterMailController extends Controller
{
    protected $newsletterMail;

    public function __construct(NewsletterMailInterface $newsletterMail)
    {
        $this->newsletterMail = $newsletterMail;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    // newsletter mail send

    public function store(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'type' => 'required',
            'mail_subject' => 'required',
            'mail_body' => 'required',
        ]);

        $data = $request;

        if ($request->type == 'SystemAdmins'){
            $users = User::query()->where('employment_status','Admin')->get();
            foreach ($users as $key => $user) {
                Mail::to($user->email)->send(new \App\Mail\NewsletterMail($data));
            }
        } elseif ($request->type == 'SystemAlumnus'){
            $users = User::query()->where('employment_status','Alumni')->get();
            foreach ($users as $key => $user) {
                Mail::to($user->email)->send(new \App\Mail\NewsletterMail($data));
            }
        } elseif ($request->type == 'SystemStudents'){
            $users = User::query()->where('employment_status','Student')->get();
            foreach ($users as $key => $user) {
                Mail::to($user->email)->send(new \App\Mail\NewsletterMail($data));
            }
        } elseif ($request->type == 'SystemCompanyHolders'){
            $users = User::query()->where('employment_status','Company')->get();
            foreach ($users as $key => $user) {
                Mail::to($user->email)->send(new \App\Mail\NewsletterMail($data));
            }
        } elseif ($request->type == 'ImportWithFile' && $request->importedFileEmailList){
            foreach ($request->importedFileEmailList as $key => $email) {
                Mail::to($email)->send(new \App\Mail\NewsletterMail($data));
            }
        } elseif ($request->recipient_user_ids && $request->type == 'Individual'){
            foreach ($request->recipient_user_ids as $userId){
                $user = User::query()->findOrFail($userId);
                Mail::to($user->email)->send(new \App\Mail\NewsletterMail($data));
            }
        }/* elseif ($request->selected_mail_list_id && $request->type == 'MailList'){
            $listDetails = CreateMailList::query()->with('importedFile')->findOrFail($request->selected_mail_list_id);
            $data = Excel::import($listDetails->importedFile->source)->get();
        }*/

        //store mail in newsletterMail table

        if($request->type == 'SystemAdmins' || $request->type == 'SystemAlumnus' || $request->type == 'SystemStudents' || $request->type == 'SystemCompanyHolders'){

            $this->newsletterMail->create($request);

        }elseif ($request->type == 'Individual' && $request->recipient_user_ids){

            $request['recipient_user_ids'] = json_encode($request->recipient_user_ids);

            $this->newsletterMail->create($request);

        } else if ($request->type == 'ImportWithFile' && $request->importedFile){
            $parameters = [
                'file_info' => [
                    [
                        'type' => 'newsletterMail',
                        'files' => $request->importedFile,
                        'directory' => 'newsletterMail',
                        'input_field' => 'importedFile',
                    ],
                ],
            ];

            $newsletterMail = $this->newsletterMail->create($request, $parameters);
        }




        return response()->json(['message' => 'Email sent successfully']);
    }

    public function show(NewsletterMail $newsletterMail)
    {
        //
    }

    public function edit(NewsletterMail $newsletterMail)
    {
        //
    }

    public function update(Request $request, NewsletterMail $newsletterMail)
    {
        //
    }

    public function destroy(NewsletterMail $newsletterMail)
    {
        //
    }
}
