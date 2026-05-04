<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{

    protected $fillable = [
        'node_id',
        'text',
        'next_node_id',
        'order',
    ];
    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    // nodo di destinazione
    public function nextNode()
    {
        return $this->belongsTo(Node::class, 'next_node_id');
    }

    public function tokens()
    {
        return $this->belongsToMany(Token::class);
    }
}
