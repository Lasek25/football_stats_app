<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteCompetition extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function competition()
    {
        return $this->belongsTo('App\Models\Competition');
    }
}
