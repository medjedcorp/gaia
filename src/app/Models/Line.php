<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    // use HasFactory;
    protected $guarded =  ['id'];

    public function train()
    {
        return $this->belongsTo(Train::class, 'company_cd');
    }
    public function stations()
    {
        return $this->hasMany(Station::class, 'line_cd');
    }
    // public function lands()
    // {
    //     return $this->hasMany(Land::class);
    // }
    // public function lands()
    // {
    //     return $this->belongsToMany(Land::class);
    // }
    public function lands()
    {
        return $this->belongsToMany(Line::class)
        ->withPivot(
            'bukken_num',
            'station_cd',
            'eki_toho',
            'eki_car',
            'eki_bus',
            'bus_toho',
            'bus_route',
            'bus_stop',
            'level');
    }
}
