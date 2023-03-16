<?php

namespace App\Http\Controllers\JobPosting;

use App\Http\Controllers\Controller;
use App\Http\Services\JobPostService;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $jobPostService;

    public function __construct(JobPostService $jobPostService)
    {
        $this->jobPostService = $jobPostService;
    }

    public function storeComment(Request $request, $id)
    {
        $comment = new Comment();
        $comment->user_id = $request->user()->id;
        $comment->comment = $request->comment;
        $comment->job_post_id = $id;
        $comment->save();

        $jobPost = app(JobPostService::class)->getJobPostWithRelations($id);
        return response()->json([$jobPost], 200);
    }

    public function updateComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update([
            'user_id' => $request->user()->id,
            'comment' => $request->input('comment'),
        ]);
        $comment->save();
        $jobPost = app(JobPostService::class)->getJobPostWithRelations($comment->job_post_id);
        return response()->json($jobPost);
    }

    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        $jobPost = app(JobPostService::class)->getJobPostWithRelations($comment->job_post_id);
        return response()->json($jobPost);
    }
}
