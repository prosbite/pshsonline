<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionTracker extends Model
{
    protected $fillable = [
        'submission_id',
        'status',
        'remarks',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
