<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'story_id',
        'name',
        'image',
    ];
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function choices()
    {
        return $this->belongsToMany(Choice::class);
    }
}
