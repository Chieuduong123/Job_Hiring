<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumVitae extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'email',
        'phone',
        'birthday',
        'link_facebook',
        'introduction',
        'work_experience',
        'education',
        'skills',
    ];

    /**
     * Get the user that owns the employer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
