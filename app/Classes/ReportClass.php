<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;

//Model
use App\Models\User;
use App\Models\MemberModel;

class ReportClass
{

    protected $userModel, $memberModel, $memberList, $userList;

    function __construct()
    {
        $this->userModel = new User();
        $this->memberModel = new MemberModel();
    }

    function generateExcel($data){
        $data = (object) $data;
        switch($data->report){
            case "ListOfEncodedMembers":
            break;

            case "ListOfMembersDuplicate":
                return $this->ListOfMembersDuplicate($data);
            break;
        }
    }

    private function ListOfMembersDuplicate($data){
        $memberInfoList = $this->memberModel->select(
            DB::raw("CONCAT(UPPER(firstname),' ',UPPER(lastname)) AS name"),
            'id',
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
        )->orderBy("name")->get();

        $memberList = $duplicateList = array();
        $duplicateMember = $this->memberModel->select(
                        DB::raw('UPPER(firstname) as firstname'),
                        DB::raw('UPPER(lastname) as lastname'),
                        DB::raw("CONCAT(UPPER(firstname),' ',UPPER(lastname)) AS name"),
                        DB::raw('COUNT(*) as count'))
                    ->groupBy('firstname', 'lastname')
                    ->havingRaw('COUNT(*) > 1')
                    ->get();
        
        foreach($duplicateMember as $duplicate){
            $duplicateList[] = $duplicate->name;
        }


        
        foreach($memberInfoList as $member){
            if(in_array($member->name, $duplicateList)){
                $memberList[$member->id] = [
                    'member_type' => $member->member_type,
                    'memid' => $member->memid,
                    'pbno' => $member->pbno,
                    'title' => $member->title,
                    'lastname' => $member->lastname,
                    'firstname' => $member->firstname,
                    'middlename' => $member->middlename,
                    'suffix' => $member->suffix,
                    'full_address' => $member->full_address,
                    'region_code' => $member->region_code,
                    'province_code' => $member->province_code,
                    'citymun_code' => $member->citymun_code,
                    'barangay_code' => $member->barangay_code,
                    'unit_floor_no' => $member->unit_floor_no,
                    'street' => $member->street,
                    'subdivision' => $member->subdivision,
                    'area' => $member->area,
                ];
            }
        }

        if($data->format == "excel"){
            $var = (array) $data;
            $var["title"] = "List Of Members Duplicate";
            $var["memberList"] = $memberList; 
            return response()->make(view("Report.Excel.ListOfMembersDuplicate",$var), '200'); 
        }
    }
}
