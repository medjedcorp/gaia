<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class land_line extends Model
{
    protected $guarded =  ['id'];
    
    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
