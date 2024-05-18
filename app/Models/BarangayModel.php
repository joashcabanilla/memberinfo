<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayModel extends Model
{
    use HasFactory;
    protected $table = 'barangay';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'brgy_code',
        'region_code',
        'province_code',
        'citymun_code',
        'name'
    ];

    function barangayList($region_code = "",$province_code = "",$citymun_code = "",$brgy_code = ""){
        $result = array();
        $barangays = $this->select("id","region_code","province_code","citymun_code","brgy_code","name");

        if(!empty($region_code)){
            $barangays = $barangays->where("region_code", $region_code);
        }

        if(!empty($province_code)){
            $barangays = $barangays->where("province_code", $province_code);
        }

        if(!empty($citymun_code)){
            $barangays = $barangays->where("citymun_code", $citymun_code);
        }

        if(!empty($brgy_code)){
            $barangays = $barangays->where("brgy_code", $brgy_code);
        }

        $barangays = $barangays->get();

        foreach($barangays as $barangay){
            $result[$barangay->id] = [
                "brgy_code" => $barangay->brgy_code,
                "name" => $barangay->name
            ];
        }
        
        return $result;
    } 
}
