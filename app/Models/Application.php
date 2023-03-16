<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seeker_id',
        'job_post_id',
        'cover_letter',
        'resume_path',
    ];

    /**
     * Get the user that owns the employer.
     */
    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
