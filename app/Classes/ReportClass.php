<?php

namespace App\Classes;

//Model
use App\Models\User;
use App\Models\MemberModel;

class ReportClass
{

    protected $userModel, $memberModel;

    function __construct()
    {
        $this->userModel = new User();
        $this->memberModel = new MemberModel();
    }

    function generateExcel($data){
        $data = (object) $data;
        dd($data);
    }
}
