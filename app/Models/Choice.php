<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    // Campi assegnabili in massa tramite create() o update().
    protected $fillable = [
        'node_id',
        'text',
        'next_node_id',
        'order',
    ];

    /**
     * Relazione: una choice appartiene a un nodo.
     * Questo è il nodo da cui parte la scelta.
     */
    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    /**
     * Relazione: nodo di destinazione della choice.
     * next_node_id indica dove porta questa scelta.
     */
    public function nextNode()
    {
        return $this->belongsTo(Node::class, 'next_node_id');
    }

    /**
     * Relazione molti-a-molti tra choices e token.
     * Una scelta può richiedere più token,
     * e un token può essere usato da più scelte.
     */
    public function tokens()
    {
        return $this->belongsToMany(Token::class);
    }
}