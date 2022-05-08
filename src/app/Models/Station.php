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
    public function land_line()
    {
        return $this->belongsTo(LandLine::class);
    }
    // public function lands()
    // {
    //     return $this->hasMany(Land::class);
    // }
    public function lands()
    {
        return $this->belongsToMany(Land::class, 'land_station', 'station_cd', 'bukken_num');
    }
}
