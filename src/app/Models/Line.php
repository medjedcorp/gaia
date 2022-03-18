<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    // use HasFactory;
    protected $guarded =  ['id'];

    public function trains()
    {
        return $this->belongsTo(Train::class);
    }
    public function stations()
    {
        return $this->hasMany(Station::class);
    }
    public function lands()
    {
        return $this->hasMany(Land::class);
    }
}
