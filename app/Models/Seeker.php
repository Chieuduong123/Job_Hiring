<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seeker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'experience',
        'education',
        'more_information',
    ];

    /**
     * Get the user that owns the employer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
