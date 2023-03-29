<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    public function index()
    {
        //
    }

    public function getSSC()
    {
        $subjects = Subject::query()->where('status','Active')->where('type','SSC')->orderBy('name')->get();

        return response()->json($subjects);
    }

    public function getHSC()
    {
        $subjects = Subject::query()->where('status','Active')->where('type','HSC')->orderBy('name')->get();

        return response()->json($subjects);
    }

    public function getGraduation()
    {
        $subjects = Subject::query()->where('status','Active')->where('type','Graduation')->orderBy('name')->get();

        return response()->json($subjects);
    }

    public function getMasters()
    {
        $subjects = Subject::query()->where('status','Active')->where('type','Masters')->orderBy('name')->get();

        return response()->json($subjects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        //
    }
}
