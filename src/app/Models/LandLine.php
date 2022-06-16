<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandLine extends Model
{
    protected $table = 'land_line';
    protected $guarded =  ['id'];
    
    public $timestamps = false;

    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
