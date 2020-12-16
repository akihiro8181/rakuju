<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\School;

class ShowSchoolInformation extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InCharge  $in_charge
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

        return Inertia::render('DataTest/SchoolInformation', [
            'school' => School::find($request->user()->school_id)->only(['id', 'name', 'workspace_url']),
        ]);
    }
}
