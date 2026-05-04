<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    public function nodes()
    {
        return $this->hasMany(Node::class);
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }
}
