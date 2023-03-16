<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class JobPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'logo',
        'employer_id',
        'job_description_id',
        'application_deadline',
        'status',
    ];
    protected $guarded = ['logo'];

    /**
     * Get the user that owns the employer.
     */
    public function jobDescription()
    {
        return $this->belongsTo(JobDescription::class);
    }

    public function jobSkill()
    {
        return $this->belongsTo(JobSkill::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function jobImages()
    {
        return $this->hasMany(JobImage::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return null;
    }
}
