<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinceModel extends Model
{
    use HasFactory;
    protected $table = 'provinces';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'region_code',
        'province_code',
        'name'
    ];

    function provinceList($region_code = "",$province_code = ""){
        $result = array();
        $provinces = $this->select("id","region_code","province_code","name");

        if(!empty($region_code)){
            $provinces = $provinces->where("region_code", $region_code);
        }

        if(!empty($province_code)){
            $provinces = $provinces->where("province_code", $province_code);
        }

        $provinces = $provinces->get();

        foreach($provinces as $province){
            $result[$province->id] = [
                "province_code" => $province->province_code,
                "name" => $province->name,
            ];
        }
        
        return $result;
    } 
}
