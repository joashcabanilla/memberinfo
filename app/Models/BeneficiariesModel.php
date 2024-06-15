<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BeneficiariesModel extends Model
{
    use HasFactory;
    protected $table = 'beneficiaries';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'memid',
        'pbno',
        'lastname',
        'firstname',
        'middlename',
        'suffix',
        'birthdate',
        'relationship',
        'contact_no',
        'created_by'
    ]; 

    function beneficiariesNumber(){
        $result = array();
        $beneficiaries = $this->select(
            'memid',
            'pbno',
            DB::raw("COUNT(lastname) AS beneficiariesNumber"),
        )->groupBy('memid','pbno')->get();
        
        foreach($beneficiaries as $beneficiary){
            $result[$beneficiary->memid."-".$beneficiary->pbno] = $beneficiary->beneficiariesNumber;
        }

        return $result;
    }

    function addBeneficiary($data){
        $data = (object) $data;
        return $this->updateOrCreate([
            "id" => !empty($data->id) ? $data->id : 0
        ],[
            "memid" => $data->memid,
            "pbno" => $data->pbno,
            "lastname" => ucwords(strtolower($data->lastname)),
            "firstname" => ucwords(strtolower($data->firstname)),
            "middlename" => ucwords(strtolower($data->middlename)),
            "suffix" => $data->suffix,
            "birthdate" => $data->birthdate,
            "relationship" => $data->relationship,
            "contact_no" => $data->contact_no,
            "created_by" => $data->created_by
        ]);
    }

    function beneficiariesTable($data){
        return $this->select(
            "id",
            "memid",
            "pbno",
            "relationship",
            DB::raw("CONCAT(COALESCE(firstname, ''), ' ', COALESCE(middlename, ''), ' ', COALESCE(lastname, '')) AS name"),
            "suffix",
            "birthdate",
            "contact_no"
        )->where("memid",$data->memid)->where("pbno",$data->pbno)->orderBy("id", "ASC");

    }
}
