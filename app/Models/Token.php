<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    // Campi assegnabili in massa tramite create() o update().
    protected $fillable = [
        'story_id',
        'name',
        'description',
        'image',
    ];

    /**
     * Relazione: un token appartiene a una storia.
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * Relazione molti-a-molti tra token e choices.
     * Un token può essere richiesto da più scelte,
     * e una scelta può richiedere più token.
     */
    public function choices()
    {
        return $this->belongsToMany(Choice::class);
    }
}