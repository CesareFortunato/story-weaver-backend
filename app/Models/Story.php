<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    // Campi assegnabili in massa tramite create() o update().
    protected $fillable = [
        'title',
        'description',
        'ambient_audio',
    ];

    /**
     * Relazione: una storia può contenere molti nodi.
     */
    public function nodes()
    {
        return $this->hasMany(Node::class);
    }

    /**
     * Relazione: una storia può contenere molti token.
     */
    public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}