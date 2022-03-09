<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    // use HasFactory;
    protected $guarded =  ['id'];

    // public function train()
    // {
    //     return $this->belongsTo(Train::class);
    // }
    public function line()
    {
        return $this->belongsTo(Line::class);
    }
}
