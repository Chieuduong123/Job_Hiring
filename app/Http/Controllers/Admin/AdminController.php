<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\CssSelector\Node\ElementNode;

class AdminController extends Controller
{
    public function approvePost($id)
    {
        $user = Auth::user();
        if ($user->role == User::ROLE_ADMIN) {
            $jobPost = JobPost::find($id);
            if ($jobPost) {
                $jobPost->status = true;
                $jobPost->save();
                return response()->json(['message' => 'Job post status updated successfully.']);
            } else {
                return response()->json(['error' => 'Job post not found.'], 404);
            }
        } else {
            return response()->json(['message' => 'You do not have permission to perform this action.']);
        }
    }
}
