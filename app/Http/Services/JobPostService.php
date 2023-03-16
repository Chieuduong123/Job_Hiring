<?php

namespace App\Http\Services;

use App\Models\JobPost;
use Illuminate\Support\Collection;

class JobPostService
{
    public function getJobPostWithRelations(int $id): Collection
    {
        return JobPost::with([
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
            ->where('id', '=', $id)
            ->get()
            ->makeHidden(['created_at', 'updated_at', 'deleted_at', 'id', 'employer_id', 'job_description_id', 'status'])
            ->each(function ($jobPost) {
                $jobPost->comments->makeHidden(['user_id', 'job_post_id', 'created_at', 'updated_at', 'deleted_at', 'id']);
                $jobPost->jobDescription->makeHidden(['id']);
                $jobPost->jobSkill->makeHidden(['id']);
                $jobPost->employer->makeHidden(['user_id', 'company_id', 'created_at', 'updated_at', 'deleted_at', 'id']);
                foreach ($jobPost->comments as $comment) {
                    $comment->user->makeHidden(['id', 'email', 'email_verified_at', 'phone', 'role', 'last_activity', 'created_at', 'updated_at']);
                }
                $jobPost->employer->company->makeHidden(['id']);
            });
    }
}
