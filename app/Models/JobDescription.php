<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class JobDescription extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'position',
        'type',
        'level',
        'salary',
    ];

    /**
     * Get the user that owns the employer.
     */

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'id');
    }
}
