<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;

class SearchJobController extends Controller
{
    public function searchJob(Request $request)
    {
        $query = JobPost::query();
        if ($request->has('position')) {
            $query->whereHas('jobDescription', function ($q) use ($request) {
                $q->where('position', 'LIKE', '%' . $request->input('position') . '%');
            });
        }
        if ($request->has('type')) {
            $query->whereHas('jobDescription', function ($q) use ($request) {
                $q->where('type', 'LIKE', '%' . $request->input('type') . '%');
            });
        }
        if ($request->has('level')) {
            $query->whereHas('jobDescription', function ($q) use ($request) {
                $q->where('level', 'LIKE', '%' . $request->input('level') . '%');
            });
        }
        if ($request->has('title')) {
            $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
        }

        $results = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($results);
    }
}
