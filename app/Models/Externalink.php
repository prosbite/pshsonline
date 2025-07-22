<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Externalink extends Model
{
    protected $fillable = [
        'link',
        'name',
        'club_register_id',
    ];

    public function clubRegister()
    {
        return $this->belongsTo(ClubRegister::class);
    }
}
