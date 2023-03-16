<?php

namespace App\Http\Controllers\JobApply;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobPost;
use App\Models\Seeker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobApplyController extends Controller
{

    public function apply(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role == User::ROLE_SEEKER) {
            $jobPost = JobPost::findOrFail($id);

            $seeker = Seeker::where('user_id', $user->id)->first();
            if (!$seeker) {
                $seeker = new Seeker();
                $seeker->user_id = $user->id;
                $seeker->experience = $request->input('experience');
                $seeker->education = $request->input('education');
                $seeker->more_information = $request->input('more_information');
                $seeker->user()->associate($user);
                $seeker->save();
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'resume_path' => 'required|mimes:doc,docx,pdf,txt|max:2048',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
            if ($files = $request->file('resume_path')) {
                $file = $request->file('resume_path');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('cv'), $filename);
                $application = new Application();
                $application->fill([
                    $application->seeker_id = $seeker->id,
                    $application->job_post_id = $jobPost->id,
                    $application->cover_letter = $request->input('cover_letter'),
                    $application->resume_path = $filename,
                ]);
                $application->save();
            }
            return response()->json("CV successfully uploaded");
        } else {
            return response()->json(['message' => 'Unauthorised'], 401);
        }
    }
}
