<?php

namespace App\Http\Services;

use App\Models\Application;
use App\Models\JobPost;
use App\Models\Seeker;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplyCVService
{
    public function applyForJob($request, $jobPostId)
    {
        $user = Auth::user();
        if ($user->role == 2) {
            $jobPost = JobPost::findOrFail($jobPostId);

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
            return $application;
        } else {
            return response()->json(['message' => 'Unauthorised'], 401);
        }
    }
}
