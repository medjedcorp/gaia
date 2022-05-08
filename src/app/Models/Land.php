<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    // use HasFactory;
    protected $guarded =  ['id'];

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }
    public function lines()
    {
        return $this->belongsToMany(Line::class)
            ->withPivot(
                'bukken_num',
                'line_cd',
                'station_cd',
                'eki_toho',
                'eki_car',
                'eki_bus',
                'bus_toho',
                'bus_route',
                'bus_stop',
                'level'
            );
    }
    public function stations()
    {
        return $this->belongsToMany(Station::class);
    }
    public function trains()
    {
        return $this->belongsToMany(Train::class);
    }
    public function scopeActiveLand($query)
    {
        return $query->where('display_flag', '=', '1');
    }

    // Scope
    public function scopeSetLatLng($query) // 現在地との距離を取得できるようにしてます
    {
        $query->where('display_flag', '=', '1')->selectRaw(
            'id, bukken_num, bukken_shumoku, price, land_menseki, kenpei_rate, youseki_rate, kenchiku_jyouken, address1, address2, address3, photo1, ST_X( location ) As latitude, ST_Y( location ) As longitude'
        );
    }

    // // Scope
    // public function scopeSelectDistance($query, $longitude, $latitude) // 現在地との距離を取得できるようにしてます
    // {
    //     $query->selectRaw(
    //         'id, bukken_num, bukken_shumoku, price, land_menseki, kenpei_rate, youseki_rate, kenchiku_jyouken, address1, address2, address3' .
    //             'ST_Y(location) AS longitude, ST_X(location) AS latitude, ' .
    //             'st_distance_sphere(POINT(?, ?), POINT(ST_Y(location), ST_X(location)))  AS distance',
    //         [$longitude, $latitude,]
    //     );
    // }

    // // Accessor
    // public function getMapUrlAttribute()
    // {
    //     return 'https://www.google.com/maps/search/?api=1&query=' . $this->latitude . ',' . $this->longitude;
    // }

    // // Mutator
    // public function setLocationAttribute($values)
    // {
    //     $point = 'POINT(' . $values['latitude'] . ' ' . $values['longitude'] . ')';
    //     $this->attributes['location'] = DB::raw('ST_GeomFromText("' . $point . '")');
    // }
}
