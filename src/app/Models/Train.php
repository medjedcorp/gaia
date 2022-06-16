<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    // use HasFactory;
    protected $guarded =  ['id'];
    protected $table = 'trains';

    public function lines()
    {
        return $this->hasMany(Line::class, 'company_cd');
    }
    
    // public function lands()
    // {
    //     return $this->hasMany(Land::class);
    // }
    public function lands()
    {
        return $this->belongsToMany(Land::class, 'land_train', 'company_cd', 'bukken_num');
    }
    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
