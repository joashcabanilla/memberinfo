<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MemberModel extends Model
{
    use HasFactory;
    protected $table = 'members';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'member_type',
        'memid',
        'pbno',
        'title',
        'lastname',
        'firstname',
        'middlename',
        'suffix',
        'full_address',
        'region_code',
        'province_code',
        'citymun_code',
        'barangay_code',
        'unit_floor_no',
        'street',
        'subdivision',
        'area',
        'updated_by'
    ];

    function memberTypeList(){
        $result = array();
        $memberType = $this->select("member_type")->distinct()->get();
        foreach($memberType as $data){
            $result[] =  $data->member_type;    
        }
        return $result;
    }

    function titleList(){
        $result = array();
        $titleList = $this->select("title")->distinct()->get();
        foreach($titleList as $data){
            $result[] =  $data->title;    
        }
        return $result;
    }

    function suffixList(){
        $result = array();
        $suffixList = $this->select("suffix")->distinct()->orderBy("suffix")->get();
        foreach($suffixList as $data){
            if(!empty($data->suffix)){
                $result[] =  $data->suffix;
            }
        }
        return $result;
    }

    function memberTable($data){
        $query = $this->select(
            "id",
            "member_type",
            "memid",
            "pbno",
            "title",
            DB::raw("CONCAT(COALESCE(firstname, ''), ' ', COALESCE(middlename, ''), ' ', COALESCE(lastname, '')) AS name"),
            "suffix",
            "full_address",
            "updated_by"
        );

        if(!empty($data->filterSearch)){
            $search = $data->filterSearch;
            $query->where(function($q) use($search){
                $q->orWhereRaw("CONCAT(COALESCE(FirstName, ''), ' ', COALESCE(MiddleName, ''), ' ', COALESCE(LastName, '')) LIKE '%".$search."%'");
                $q->orWhereRaw("full_address LIKE '%".$search."%'");
            });
        }

        $query = !empty($data->filterMemberType) ? $query->where("member_type", $data->filterMemberType) : $query;

        if(!empty($data->filterStatus)){
            if($data->filterStatus == "updated"){
                $query = $query->whereNotNull("region_code");
            }else{
                $query = $query->whereNull("region_code");
            }
        }
        $query = $query->orderBy("id", "ASC");

        return $query;
    }

    function createUpdateMember($data){
        $result = array();
        $result["status"] = "success";
        $rules = [
            'member_type' => ['required'],
            'title' => ['required'],
            'firstname' => ['required'],
            'lastname' => ['required'],
            'full_address' => ['required'],
            'region_code' => ['required'],
            'province_code' => ['required'],
            'citymun_code' => ['required'],
            'barangay_code' => ['required'],
            'street' => ['required'],
            'updated_by' => ['required']
        ];

        $validator = Validator::make($data,$rules);

        if($validator->fails()){
            $result["error"] = $validator->errors();
            $result["status"] = "failed";
        }else{
            $this->updateOrCreate([
                "id" => !empty($data["id"]) ? $data["id"] : 0
            ],$data);
        }
    }

    function getMember($id){
        return $this->find($id);
    }
}
