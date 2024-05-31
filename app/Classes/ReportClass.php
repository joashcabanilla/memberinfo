<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;

//Model
use App\Models\User;
use App\Models\MemberModel;
use App\Models\RegionModel;
use App\Models\ProvinceModel;
use App\Models\CityModel;
use App\Models\BarangayModel;

class ReportClass
{

    protected $userModel, $memberModel, $regionModel, $provinceModel, $cityModel, $barangayModel;

    function __construct()
    {
        $this->userModel = new User();
        $this->memberModel = new MemberModel();
        $this->regionModel = new regionModel();
        $this->provinceModel = new provinceModel();
        $this->cityModel = new cityModel();
        $this->barangayModel = new barangayModel();
    }

    function generateReport($data){
        $data = (object) $data;
        switch($data->report){
            case "ListOfEncodedMembers":
                return $this->ListOfEncodedMembers($data);
            break;

            case "ListOfMembersDuplicate":
                return $this->ListOfMembersDuplicate($data);
            break;

            case "ListOfMembersWithUpdatedAddress":
                return $this->ListOfMembersWithUpdatedAddress($data);
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

    private function ListOfEncodedMembers($data){
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
            'updated_by',
            'updated_at'
        );

        $users = $this->userModel->getUser();
        $regionList = $this->regionModel->regionList();
        $provinces = $this->provinceModel->get();
        $cities = $this->cityModel->get();
        $barangays = $this->barangayModel->get();

        if(!empty($data->user)){
            $memberInfoList = $memberInfoList->where("updated_by",$data->user);
        }

        $memberInfoList = $memberInfoList->orderBy("member_type")->get();
        $memberList = $userList = $updateCountList = $totalCountList = array();
        $provinceList = $cityList = $barangayList = array();

        foreach($provinces as $province){
            $provinceList[$province->province_code] = $province->name;
        }

        foreach($cities as $city){
            $cityList[$city->citymun_code] = $city->name;
        }

        foreach($barangays as $barangay){
            $barangayList[$barangay->brgy_code] = $barangay->name;
        }

        foreach($users as $user){
            $userList[$user->id] = ucwords(strtolower($user->name));
        }

        foreach($memberInfoList as $member){
            $regionName = !empty($member->region_code) ? $regionList[$member->region_code] : "";
            $provinceName = !empty($member->province_code) ? $provinceList[$member->province_code] : "";
            $cityName = !empty($member->citymun_code) ? $cityList[$member->citymun_code] : "";
            $barangayName = !empty($member->barangay_code) ? $barangayList[$member->barangay_code] : "";

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
                'regionName' => $regionName,
                'provinceName' => $provinceName,
                'cityName' => $cityName,
                'barangayName' => $barangayName,
                'region_code' => $member->region_code,
                'province_code' => $member->province_code,
                'citymun_code' => $member->citymun_code,
                'barangay_code' => $member->barangay_code,
                'unit_floor_no' => $member->unit_floor_no,
                'street' => $member->street,
                'subdivision' => $member->subdivision,
                'area' => $member->area,
                'updated_by' => !empty($member->updated_by) ? $userList[$member->updated_by] : ""
            ]; 

            if(!empty($member->updated_by)){
                $dateUpdated = date("m-d-Y", strtotime($member->updated_at));
                $updateCountList[$member->updated_by][$dateUpdated][] = $member->id;
                $totalCountList[$member->updated_by][] = $member->id;
            }   
        }

        if($data->format == "excel"){
            $var = (array) $data;
            $var["title"] = "List Of Encoded Members";
            $var["memberList"] = $memberList;
            $var["updateCountList"] = $updateCountList;
            $var["totalCountList"] = $totalCountList;
            $var["userList"] = $userList;

            return response()->make(view("Report.Excel.ListOfEncodedMember",$var), '200'); 
        }
    }

    private function ListOfMembersWithUpdatedAddress($data){
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
        )->where("updated_by","!=",0)->get();

        $memberList = array();

        foreach($memberInfoList as $member){
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
                'area' => $member->area
            ];
        }

        if($data->format == "excel"){
            $var = (array) $data;
            $var["title"] = "List Of Members";
            $var["memberList"] = $memberList;
            return response()->make(view("Report.Excel.ListOfMembersWithUpdatedAddress",$var), '200'); 
        }
    }
}
