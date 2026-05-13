<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    // Campi assegnabili in massa tramite create() o update().
    protected $fillable = [
        'story_id',
        'title',
        'text',
        'image',
        'is_start',
        'position_x',
        'position_y',
    ];

    /**
     * Relazione: un nodo appartiene a una storia.
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * Relazione: un nodo può avere più scelte.
     * Le choices vengono ordinate automaticamente tramite il campo "order".
     */
    public function choices()
    {
        return $this->hasMany(Choice::class)->orderBy('order');
    }
}