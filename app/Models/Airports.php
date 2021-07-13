<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *  @method static \Illuminate\Database\Query\Builder|\App findOrFail($id, $columns = ['*'])
 *  @method static \Illuminate\Database\Query\Builder|\App where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Airports extends Model
{
    protected $table = 'airports';

    protected $fillable = [
        'latitude',
        'longitude',
        'active',
        'icao',
        'iata',
        'name',
        'location',
        'country',
        'country_code'
    ];


    public function searchIata($icao)
    {


        $airport = Airports::where('icao', '=', $icao)->first([
            'country',
            'country_code',
            'iata',
            'icao',
            'latitude',
            'longitude',
            'location',
            'name'
        ]);

        if($airport)
            return $airport->toArray();
        else
            return NULL;




    }

    public function addNew($data)
    {
        unset($data['link']);
        unset($data['status']);
        $data['active'] = 1;
        Airports::create($data);

    }


}
