<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchModel extends Model
{
    use HasFactory;
    protected $table = 'branches';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'memid',
        'pbno',
        'branch',
    ];

    function allMemberBranches(){
        $result = array();
        foreach($this->get() as $member){
            $memid = str_replace(" ","",str_replace(".","",$member->memid));
            $pbno = str_replace(" ","",str_replace(".","",$member->pbno));
            $result[$memid."-".$pbno] = $member->branch;
        }
        return $result;
    }
}
