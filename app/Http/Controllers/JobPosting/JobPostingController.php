<?php

namespace App\Http\Controllers\JobPosting;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employer;
use App\Models\JobDescription;
use App\Models\JobImage;
use App\Models\JobPost;
use App\Models\JobSkill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostingController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->role == User::ROLE_EMPLOYER) {
            $company = Company::where([
                ['name', $request->input('name')],
                ['location', $request->input('location')],
                ['website', $request->input('website')],
            ])->first();
            if (!$company) {
                $company = new Company();
                $company->fill([
                    $company->name = $request->input('name'),
                    $company->location = $request->input('location'),
                    $company->website = $request->input('website'),
                ]);
                $company->save();
            }

            $employer = Employer::where('user_id', $user->id)->first();
            if (!$employer) {
                $employer = new Employer();
                $employer->user_id = $user->id;
                $employer->company_id = $company->id;
                $employer->user()->associate($user);
                $employer->save();
            }

            $jobDescription = new JobDescription();
            $jobDescription->fill([
                $jobDescription->position = $request->input('position'),
                $jobDescription->type = $request->input('type'),
                $jobDescription->level = $request->input('level'),
                $jobDescription->salary = $request->input('salary'),
            ]);
            $jobDescription->save();

            $jobSkill = new JobSkill();
            $jobSkill->fill([
                $jobSkill->requirement = $request->input('requirement'),
                $jobSkill->skill_description = $request->input('skill_description'),
            ]);
            $jobSkill->save();

            $employer->company()->associate($company);
            $employer->save();


            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
                $jobPost = new JobPost();
                $jobPost->fill([
                    $jobPost->title = $request->input('title'),
                    $jobPost->content = $request->input('content'),
                    $jobPost->employer_id = $employer->id,
                    $jobPost->application_deadline = $request->input('application_deadline'),
                    $jobPost->status = false,
                    $jobPost->logo = $filename,
                ]);
                $jobPost->jobDescription()->associate($jobDescription);
                $jobPost->jobSkill()->associate($jobSkill);
                $jobPost->save();
            }

            if ($request->hasFile('image_path')) {
                $files = $request->file("image_path");
                foreach ($files as $file) {
                    $imageName = time() . '_' . $file->getClientOriginalName();
                    $jobImage = new JobImage([
                        'job_post_id' => $jobPost->id,
                        'image_path' => $imageName,
                        'title' => "abc",
                    ]);
                    $file->move(public_path("images"), $imageName);
                    $jobPost->jobImages()->save($jobImage);
                }
            }
        } else {
            return response()->json(['message' => 'Unauthorised'], 401);
        }
        return response()->json(['message' => 'Job post created successfully', $jobPost, $employer, $company, $jobImage]);
    }

    public function getJobPosting()
    {
        $showJobPost = JobPost::with([
            'jobDescription' => function ($query) {
                $query->select('position', 'type', 'salary', 'id');
            },
            'jobSkill' => function ($query) {
                $query->select('requirement', 'skill_description', 'id');
            },
            'employer.user' => function ($query) {
                $query->select('name', 'id');
            },
            'employer.company' => function ($query) {
                $query->select('name', 'location', 'website', 'id');
            },
            'comments'
        ])
            ->where('status', true)
            ->get()
            ->makeHidden(['created_at', 'updated_at', 'id', 'employer_id', 'job_description_id', 'status'])
            ->each(function ($jobPost) {
                $jobPost->jobDescription->makeHidden(['id']);
                $jobPost->jobSkill->makeHidden(['id']);
                $jobPost->employer->makeHidden(['user_id', 'company_id', 'created_at', 'updated_at', 'id']);
                $jobPost->employer->company->makeHidden(['id']);
                $jobPost->comments->makeHidden(['user_id', 'job_post_id', 'created_at', 'updated_at', 'deleted_at', 'id']);
                foreach ($jobPost->comments as $comment) {
                    $comment->user->makeHidden(['id', 'email', 'email_verified_at', 'phone', 'role', 'last_activity', 'created_at', 'updated_at']);
                }
            });
        return response()->json($showJobPost);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role == User::ROLE_EMPLOYER) {
            $jobPost = JobPost::findOrFail($id);
            $jobDescription = $jobPost->jobDescription;
            $jobSkill = $jobPost->jobSkill;
            $employer = $jobPost->employer;
            $company = $employer->company;
            $jobImages = $jobPost->jobImages;

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
                $jobPost->logo = $filename;
            }
            $jobPost->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'application_deadline' => $request->input('application_deadline'),
                'status' => false,
            ]);

            $jobDescription->update([
                'position' => $request->input('position'),
                'type' => $request->input('type'),
                'level' => $request->input('level'),
                'salary' => $request->input('salary'),
            ]);

            $jobSkill->update([
                'requirement' => $request->input('requirement'),
                'skill_description' => $request->input('skill_description'),
            ]);

            $company->update([
                'name' => $request->input('name'),
                'location' => $request->input('location'),
                'website' => $request->input('website'),
            ]);

            if ($request->hasFile('image_path')) {
                $files = $request->file("image_path");
                foreach ($files as $file) {
                    $imageName = time() . '_' . $file->getClientOriginalName();
                    $jobImage = new JobImage([
                        'job_post_id' => $jobPost->id,
                        'image_path' => $imageName,
                        'title' => "abc",
                    ]);
                    $file->move(public_path("images"), $imageName);
                    $jobPost->jobImages()->save($jobImage);
                }
            }

            $jobPost->save();
            $jobDescription->save();
            $jobSkill->save();
            $employer->save();
            $company->save();

            foreach ($jobImages as $jobImage) {
                $jobImage->save();
            }

            return response()->json(['message' => 'Job post updated successfully', 'jobPost' => $jobPost]);
        } else {
            return response()->json(['message' => 'Unauthorised'], 401);
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if ($user->role == User::ROLE_EMPLOYER) {
            $jobPost = JobPost::findOrFail($id);
            $jobDescription = $jobPost->jobDescription;
            $jobSkill = $jobPost->jobSkill;
            $jobImages = $jobPost->jobImages;
            $employer = $jobPost->employer;

            $jobPost->delete();
            $jobDescription->delete();
            $jobSkill->delete();
            $jobImages->each(function ($jobImage) {
                $jobImage->delete();
            });
            if ($employer->company) {
                $employer->company->delete();
            }

            return response()->json(['message' => 'Job post, job description, job image, and company deleted successfully']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
