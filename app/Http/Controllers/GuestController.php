<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//Model
use App\Models\User;
use App\Models\MemberModel;

class GuestController extends Controller
{
    protected $data, $userModel, $memberModel;

    public function __construct()
    {
        $this->middleware('guest');
        $this->data = array();
        $this->userModel = new User();
        $this->memberModel = new MemberModel();
    }

    function Index(){
        $this->data["titlePage"] = "MEMBER'S INFO | Login";
        return view('Components.Login',$this->data);
    }

    function Email(){
        $this->data["titlePage"] = "MEMBER'S INFO | Email";
        return view('Components.Email',$this->data);
    }

    function Login(Request $request){
        return $this->userModel->login($request);
    }

    function searchMember(Request $request){
        $result["status"] = "success";
        $searchMember = array();
        $memid_pbno = $request->pbno_memid;
        $memberList = $this->memberModel->where(function($q) use($memid_pbno){
            $q->orWhere("memid", $memid_pbno);
            $q->orWhere("pbno", $memid_pbno);
        })->get();
        
        if(count($memberList) > 0){
            foreach($memberList as $member){
                $firstname = strtolower(str_replace(".","",$member->firstname));
                $lastname = strtolower(str_replace(".","",$member->lastname));
                if($firstname == strtolower($request->firstname) && $lastname == strtolower($request->lastname)){
                    $searchMember = $member;
                }
            }

            if(empty($searchMember)){
                $result["status"] = "failed";
                $result["error"] = "Incorrect first name or last name";
            }else{
                if(!empty($searchMember->email)){
                    $result["status"] = "failed";
                    $result["error"] = "The member already has an email address.";
                }else{
                    $result["member"] = $searchMember;
                }
            }
            
        }else{
            $result["status"] = "failed";
            $result["error"] = "Incorrect pbno or memid";
        }
        return $result;
    }

    function updateEmail(Request $request){
        $result["status"] = "success";
        $rules = [
            'email' => ['required', 'string', 'email', 'max:255','email:rfc,dns'],
        ];
     
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            $result["error"] = $validator->errors();
            $result["status"] = "failed";
        }else{
            $this->memberModel->find($request->id)->update(["email" => $request->email]);
        }

        return $result;
    }
}
