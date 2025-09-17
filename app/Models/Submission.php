<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    /** @use HasFactory<\Database\Factories\SubmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'remarks',
        'club_register_id',
        'user_id',
        'url',
        'status',
    ];

    public function clubRegister()
    {
        return $this->belongsTo(ClubRegister::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getSubmissionsForClubRegister($clubRegisterId)
    {
        return self::where('club_register_id', $clubRegisterId)->with('submissionTrackers.updatedBy')->orderBy('created_at', 'desc')->get();
    }

    public function submissionTrackers()
    {
        return $this->hasMany(SubmissionTracker::class);
    }
}
