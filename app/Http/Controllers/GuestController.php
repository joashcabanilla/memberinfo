<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Model
use App\Models\User;

class GuestController extends Controller
{
    protected $data, $userModel;

    public function __construct()
    {
        $this->middleware('guest');
        $this->data = array();
        $this->userModel = new User();
    }

    function Index(){
        $this->data["titlePage"] = "MEMBER'S INFO | Login";
        return view('Components.Login',$this->data);
    }

    function Login(Request $request){
        return $this->userModel->login($request);
    }
}
