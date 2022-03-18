<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    use HasFactory;

    protected $guarded = array('id');
    public $timestamps = false;

    public function lands()
    {
        return $this->hasMany(Land::class);
    }
}
