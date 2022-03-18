<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    // use HasFactory;
    protected $guarded =  ['id'];

    public function prefectures()
    {
        return $this->belongsTo(Prefecture::class);
    }
    public function lines()
    {
        return $this->belongsTo(Line::class);
    }
    public function stations()
    {
        return $this->belongsTo(Station::class);
    }
    public function trains()
    {
        return $this->belongsTo(Train::class);
    }
}
