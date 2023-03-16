<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'comments';
    protected $fillable = [
        'user_id',
        'job_post_id',
        'comment',
    ];
    protected $appends = ['name'];

    /**
     * Get the user that owns the employer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
    public function getNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return null;
    }
}
