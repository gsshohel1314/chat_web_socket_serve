<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getSSC()
    {
        $institutes = Institute::query()->where('status','Active')->where('type','SSC')->orderBy('name')->get();

        return response()->json($institutes);
    }

    public function getHSC()
    {
        $institutes = Institute::query()->where('status','Active')->where('type','HSC')->orderBy('name')->get();

        return response()->json($institutes);
    }

    public function getGraduation()
    {
        $institutes = Institute::query()->where('status','Active')->where('type','Graduation')->orderBy('name')->get();

        return response()->json($institutes);
    }

    public function getMasters()
    {
        $institutes = Institute::query()->where('status','Active')->where('type','Masters')->orderBy('name')->get();

        return response()->json($institutes);
    }

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
     * @param  \App\Models\Institute  $institute
     * @return \Illuminate\Http\Response
     */
    public function show(Institute $institute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Institute  $institute
     * @return \Illuminate\Http\Response
     */
    public function edit(Institute $institute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Institute  $institute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Institute $institute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Institute  $institute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Institute $institute)
    {
        //
    }
}
