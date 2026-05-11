<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = [
        'story_id',
        'title',
        'text',
        'image',
        'is_start',
        'position_x',
        'position_y',
    ];
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function choices()
    {
        return $this->hasMany(Choice::class)->orderBy('order');
    }
}
