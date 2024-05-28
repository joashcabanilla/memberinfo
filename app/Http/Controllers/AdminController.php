<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//Classes
use App\Classes\DataTableClass;
use App\Classes\ReportClass;

//Model
use App\Models\User;
use App\Models\MemberModel;
use App\Models\RegionModel;
use App\Models\ProvinceModel;
use App\Models\CityModel;
use App\Models\BarangayModel;

class AdminController extends Controller
{
    protected $data, $datatable, $userModel, $memberModel, $regionModel, $provinceModel, $cityModel, $barangayModel, $reportClass;

    public function __construct()
    {
        $this->middleware('auth');
        $this->data = array();
        $this->userModel = new User();
        $this->datatable = new DataTableClass();
        $this->memberModel = new MemberModel();
        $this->regionModel = new RegionModel();
        $this->provinceModel = new ProvinceModel();
        $this->cityModel = new CityModel();
        $this->barangayModel = new BarangayModel();
        $this->reportClass = new ReportClass();
    }

    function Users(){
        $this->data["titlePage"] = "MEMBER'S INFO | Users";
        $this->data["tab"] = "users"; 
        return view('Components.Users',$this->data);
    }

    function Maintenance(){
        $this->data["titlePage"] = "MEMBER'S INFO | Maintenance";
        $this->data["tab"] = "maintenance";

        $tableArray = $this->datatable->getAllDatabaseTable();
        $tableList = array();
        foreach($tableArray as $table){
            foreach($table as $tablename){
                $tableList[] = trim($tablename);
            }
        }
        $this->data["tables"] = $tableList;

        $this->data['reportList'] = [
            "ListOfEncodedMembers" => "List Of Encoded Members",
            "ListOfMembersDuplicate" => "List Of Members Duplicate",
            "ListOfMembersWithUpdatedAddress" => "List Of Members With Updated Address",
        ];

        $userList = $this->userModel->getUser();
        foreach($userList as $user){
            $this->data['userList'][$user->id] = $user->name;
        }

        return view('Components.Maintenance',$this->data);
    }

    function Members(){
        $this->data["titlePage"] = "MEMBER'S INFO | Members";
        $this->data["tab"] = "members";
        $this->data["memberTypeList"] = $this->memberModel->memberTypeList();
        $this->data["titleList"] = $this->memberModel->titleList();
        $this->data["suffixList"] = $this->memberModel->suffixList();
        $this->data["regionList"] = $this->regionModel->regionList();
        return view('Components.Members',$this->data);
    }

    function Logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response('logout',200); 
    }

    function UserTable(Request $request){
        return $this->datatable->userTable($request->all());
    }

    function createUpdateUser(Request $request){
        return $this->userModel->createUpdateUser($request->all());
    }

    function getUser(Request $request){
        return $this->userModel->getUser($request->id);
    }

    function deactivateUser(Request $request){
        if(!empty($request->status)){
            return $this->userModel->deactivateUser($request->id, $request->status);
        }else{
            return $this->userModel->deactivateUser($request->id);
        }
    }

    function batchInsertData(Request $request){
        $table = $request->table;
        $data = $request->insert;
        $result = array();
    
        if(!empty($data)){
            foreach($data as $rowData){
                foreach($rowData as $key => $row){
                    $dbData[trim($key)] = !empty($row) ? trim($row) : NULL;
                }
                $insertData[] = $dbData;
            }
            $dbInsert = DB::table(trim($table))->insert($insertData);
            if($dbInsert){
                $result["status"] = "success";
            }else{
                $result["status"] = "failed";
                $result["error"] = $insertData;
            }
        }else{
            $result["status"] = "failed";
            $result["error"] = $data;
        }

        return $result;
    }

    function memberTable(Request $request){
        return $this->datatable->memberTable($request->all());
    }

    function getProvinces(Request $request){
        return $this->provinceModel->provinceList($request->region_code);
    }

    function getCities(Request $request){
        return $this->cityModel->cityList($request->region_code, $request->province_code);
    }

    function getBarangay(Request $request){
        return $this->barangayModel->barangayList($request->region_code,$request->province_code,$request->citymun_code);
    }

    function createUpdateMember(Request $request){
        return $this->memberModel->createUpdateMember($request->all());
    }

    function getMember(Request $request){
        return $this->memberModel->getMember($request->id);
    }

    function generateReport(Request $request){
        return $this->reportClass->generateReport($request->all());
    }
}
