<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'region_code',
        'province_code',
        'citymun_code',
        'name'
    ];

    function cityList($region_code = "",$province_code = "",$citymun_code = ""){
        $result = array();
        $cities = $this->select("id","region_code","province_code","citymun_code","name");

        if(!empty($region_code)){
            $cities = $cities->where("region_code", $region_code);
        }

        if(!empty($province_code)){
            $cities = $cities->where("province_code", $province_code);
        }

        if(!empty($citymun_code)){
            $cities = $cities->where("citymun_code", $citymun_code);
        }

        $cities = $cities->get();

        foreach($cities as $city){
            $result[$city->id] = [
                "citymun_code" => $city->citymun_code,
                "name" => $city->name,
            ];
        }
        
        return $result;
    } 
}
