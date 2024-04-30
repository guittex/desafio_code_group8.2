<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sorteios extends Model
{
    use HasFactory;

    public function jogador()
    {
        return $this->belongsTo(Jogadores::class);
    }
}
