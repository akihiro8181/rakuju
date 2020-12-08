<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\School;
use App\Http\Controllers\Controller;
use App\Actions\UpdateSchoolInformation as ActionUpdateSchoolInformation;

class UpdateSchoolInformation extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, School $school)
    {
        app(ActionUpdateSchoolInformation::class)->update($request->user(), $school, $request->all());

        return back(303)->with('flash', [
            'message' => "update SchoolInformation",
        ]);
    }
}
