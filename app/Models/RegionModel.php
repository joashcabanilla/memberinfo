<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionModel extends Model
{
    use HasFactory;
    protected $table = 'regions';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'region_code',
        'name'
    ];

    function regionList($code = ""){
        $result = array();

        if(!empty($code)){
            $regions = $this->where("region_code", $code)->get();    
        }else{
            $regions = $this->get();   
        }

        foreach($regions as $region){
            $result[$region->region_code] = $region->name;
        }
        
        return $result;
    } 
}
