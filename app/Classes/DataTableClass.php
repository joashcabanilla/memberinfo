<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

//Model
use App\Models\User;
use App\Models\MemberModel;
use App\Models\DependentModel;
use App\Models\BeneficiariesModel;
use App\Models\RelationshipModel;

class DataTableClass
{
    protected $userModel, $memberModel, $dependentModel, $beneficiariesModel, $relationshipModel;

    function __construct()
    {
        $this->userModel = new User();
        $this->memberModel = new MemberModel();
        $this->dependentModel = new DependentModel();
        $this->beneficiariesModel = new BeneficiariesModel();
        $this->relationshipModel = new RelationshipModel();
    }

    function getAllDatabaseTable(){
        $tables = DB::select('SHOW TABLES');
        return $tables;
    }
    
    function processTable($param){
        $final_query = $param['sql'];
        $columns = $param['columns'];
        $result['iTotalRecords'] = 0;
        $param['union'] = !empty($param['union']) ? $param['union'] : array() ;
        $counter = 0;
   
        if(isset($param['group'])&&$param['group']):
            $result["iTotalRecords"] = count($param['sql']->groupBy($param['group'])->distinct($param['group'])->get());
        elseif(isset($param['having'])&&$param['having']):
            $result["iTotalRecords"] = count($param['sql']->having($param['having'][0][0],$param['having'][0][1],$param['having'][0][2])->get());
        elseif(isset($param['distinct'])&&$param['distinct']):
            if(isset($param['union']) && $param['union']):
                if(count($param['union'])>0):
                    foreach($param['union'] as $unions):
                        $counter++;
   
                        $result["iTotalRecords"] += $unions->distinct($param['distinct'])->count();
                        if($counter!=1):
                            $final_query = $final_query->unionAll($unions);
                        endif;
                    endforeach;
                endif;
            else:
                $result["iTotalRecords"] = $param['sql']->distinct($param['distinct'])->count();
            endif;
   
        else:
            $result["iTotalRecords"] = $param['sql']->count();
        endif;
        if( $param['var']->length > 0 ){
            $final_query = $final_query->skip(intval($param['var']->start))->take(intval($param['var']->length));
        }
   
        $result["iTotalDisplayRecords"] = $result["iTotalRecords"];
   
        if(isset($param['group'])&&$param['group']):
            $tmpgroup = is_array($param['group'])?$param['group']:[$param['group']];
            $final_query = call_user_func_array([$final_query,'groupBy'],$tmpgroup);
        endif; 
        if(isset($param['having'])&&$param['having']):
            foreach ($param['having'] as $con):
                $final_query = call_user_func_array([$final_query,'having'],$con);
            endforeach;
        endif;
        if(isset($param['distinct'])&&$param['distinct']) $final_query->distinct();
   
   
        $result["aaData"] = array();
        $count = intval($param['var']->start?$param['var']->start:0);
        
        foreach ($final_query->get() as $finres){
            $count ++;
            $isAModel = is_a($finres,'Illuminate\Database\Eloquent\Model');
            $mrow = $isAModel ? $finres : (array) $finres;
   
            $tmpr = array();
            foreach ($columns as $cc=>$cval) {
                $val = $mrow[ $cval['db'] ];
   
                if(isset($cval['sortnum'])&&$cval['sortnum']){
                    $tmpr[] = $count;
                }else if ( isset( $cval['formatter'] ) ) {
                    $tmpr[] = $cval['formatter']( $val, $mrow);
                }else {
                    $tmpr[] = $val;
                }
            }
            $result["aaData"][] = $tmpr;
        }
   
        echo json_encode($result);
    }

    function userTable($data){
        $var = (object) $data;
        $query = $this->userModel->userTable($var);
        $columns = [
            ['db' => 'id', 'dt' => 0,'orderable' => false, 'sortnum'=>true],

            ['db' => 'user_type', 'dt' => 1,'formatter' => function($d){
                return strtoupper($d);
            }],

            ['db' => 'name', 'dt' => 2,'formatter' => function($d){
                return strtoupper($d);
            }],

            ['db' => 'deleted_at', 'dt' => 3,'formatter' => function($d){
                $status = !empty($d) ? "DEACTIVATED" : "ACTIVE";
                $color =   $status != "ACTIVE" ? "border border-danger text-danger" : "border border-success text-success";
                return "<p style='font-size: 0.9rem !important;' class='text-center font-weight-bold m-0 p-1 rounded-lg elevation-1 ".$color."'>".$status."</p>";
            }],

            ['db' => 'last_login', 'dt' => 4,'formatter' => function($d){
                return !empty($d) ? date("m/d/Y h:i A", strtotime($d)) : "";
            }],

            ['db' => 'last_ip', 'dt' => 5],

            ['db' => 'id', 'dt' => 6, 'formatter' => function($d, $userData){
                if(!empty($userData->deleted_at)){
                    $deactivate = "<a class='dropdown-item activateBtn' style='cursor:pointer;' data-id='".$d."'><i class='fas fa-user-check'></i> Activate</a>";
                }else{
                    $deactivate = "<a class='dropdown-item deactivateBtn' style='cursor:pointer;' data-id='".$d."'><i class='fas fa-user-lock'></i> Deactivate</a>";
                }

                return "<div class='btn-group'>
                <button type='button' class='btn btn-sm' data-toggle='dropdown'><i class='fas fa-ellipsis-h'></i>
                </button>
                <div class='dropdown-menu dropdown-menu dropdown-menu-left'>
                  <a class='dropdown-item editBtn' style='cursor:pointer;' data-id='".$d."'><i class='fas fa-edit'></i> Edit</a>
                  ".$deactivate."
                </div>
              </div>";
            }]
        ];

        $params = array(
            "var" => $var,
            "columns" => $columns,
            "sql" => $query  
        );
        
        return $this->processTable($params);
    }
    
    function memberTable($data){
        $var = (object) $data;
        $query = $this->memberModel->memberTable($var);
        $user = Auth::user()->user_type;

        $columns = [
            ['db' => 'id', 'dt' => 0,'orderable' => false, 'sortnum'=>true],

            ['db' => 'member_type', 'dt' => 1,'formatter' => function($d){
                return strtoupper($d);
            }],

            ['db' => 'memid', 'dt' => 2],

            ['db' => 'pbno', 'dt' => 3],

            ['db' => 'name', 'dt' => 4,'formatter' => function($d,$tableData){
                $name = $tableData["title"]." ".$d;
                if(!empty($tableData["suffix"])){
                    $name = $name." ".$tableData["suffix"];
                }    
                return ucwords(strtolower($name));
            }],

            ['db' => 'full_address', 'dt' => 5],

            ['db' => 'id', 'dt' => 6, 'formatter' => function($d,$drow) use($user){
                $status = $drow["updated_by"] != 0 ? "updated" : "notupdated";
                $deleteBtn = "";
                $id = "";
                if($user == "admin"){
                    $deleteBtn = "<button type='submit' class='btn btn-sm btn-primary elevation-1 deleteBtn mt-1' data-status='".$status."' data-id='".$d."'><i class='fas fa-trash' aria-hidden='true'></i></button>";
                    $id = "<p>".$d."<p>";
                }

                return "<button type='submit' class='btn btn-sm btn-primary elevation-1 editBtn' data-status='".$status."' data-id='".$d."'><i class='fas fa-edit' aria-hidden='true'></i></button>".$deleteBtn.$id;
            }],
        ];

        $params = array(
            "var" => $var,
            "columns" => $columns,
            "sql" => $query  
        );
        
        return $this->processTable($params);
    }

    function dependentTable($data){
        $var = (object) $data;
        $dependentsNumber = $this->dependentModel->dependentsNumber();
        $beneficiariesNumber = $this->beneficiariesModel->beneficiariesNumber();

        $query = $this->memberModel->memberTable($var);
        $columns = [
            ['db' => 'id', 'dt' => 0,'orderable' => false, 'sortnum'=>true],

            ['db' => 'member_type', 'dt' => 1,'formatter' => function($d){
                return strtoupper($d);
            }],

            ['db' => 'memid', 'dt' => 2],

            ['db' => 'pbno', 'dt' => 3],

            ['db' => 'name', 'dt' => 4,'formatter' => function($d,$tableData){
                $name = $tableData["title"]." ".$d;
                if(!empty($tableData["suffix"])){
                    $name = $name." ".$tableData["suffix"];
                }    
                return ucwords(strtolower($name));
            }],

            ['db' => 'id', 'dt' => 5, 'formatter' => function($d,$drow) use($dependentsNumber){
                return isset($dependentsNumber[$drow["memid"]."-".$drow["pbno"]]) ? $dependentsNumber[$drow["memid"]."-".$drow["pbno"]] : 0;
            }],

            ['db' => 'id', 'dt' => 6, 'formatter' => function($d,$drow) use($beneficiariesNumber){
                return isset($beneficiariesNumber[$drow["memid"]."-".$drow["pbno"]]) ? $beneficiariesNumber[$drow["memid"]."-".$drow["pbno"]] : 0;
            }],

            ['db' => 'id', 'dt' => 7, 'formatter' => function($d,$drow){
                $memberName = $drow["name"]." ".$drow["suffix"];

                return "<div class='btn-group'>
                <button type='button' class='btn btn-sm' data-toggle='dropdown'><i class='fas fa-ellipsis-h'></i></button>
                <div class='dropdown-menu dropdown-menu dropdown-menu-left'>
                  <a class='dropdown-item dependents-editBtn' style='cursor:pointer;' data-id='".$d."' data-membername='".$memberName."' data-memid='".$drow["memid"]."' data-pbno='".$drow["pbno"]."'><i class='fas fa-edit'></i> Dependents</a> 
                  <a class='dropdown-item beneficiaries-editBtn' style='cursor:pointer;' data-id='".$d."' data-membername='".$memberName."' data-memid='".$drow["memid"]."' data-pbno='".$drow["pbno"]."'><i class='fas fa-edit'></i> Beneficiaries</a>
                </div>
              </div>";
            }],
        ];

        $params = array(
            "var" => $var,
            "columns" => $columns,
            "sql" => $query  
        );
        
        return $this->processTable($params);
    }

    function dependentBeneficiariesTable($data){
        $var = (object) $data;
        
        $relationshipList = $this->relationshipModel->relationshipListTable();

        if($var->action == "dependents"){
            $query = $this->dependentModel->dependentTable($var);
        }else{
            $query = $this->beneficiariesModel->beneficiariesTable($var);
        }

        $columns = [
            ['db' => 'id', 'dt' => 0,'orderable' => false, 'sortnum'=>true],

            ['db' => 'name', 'dt' => 1,'formatter' => function($d,$tableData){
                $suffix = "";
                if(!empty($tableData["suffix"])){
                    $suffix = $tableData["suffix"];
                }    
                $name = ucwords(strtolower($d))." ".$suffix;
                return trim($name);
            }],

            ['db' => 'birthdate', 'dt' => 2, 'formatter' => function($d){
                return !empty($d) ? date("m/d/Y", strtotime($d)) : "";
            }],

            ['db' => 'contact_no', 'dt' => 3],

            ['db' => 'relationship', 'dt' => 4, 'formatter' => function($d) use($relationshipList){
                return $relationshipList[$d];
            }],

            ['db' => 'id', 'dt' => 5, 'formatter' => function($d){
                return "<div class='btn-group'>
                <button type='button' class='btn btn-sm' data-toggle='dropdown'><i class='fas fa-ellipsis-h'></i></button>
                <div class='dropdown-menu dropdown-menu dropdown-menu-left'>
                  <a class='dropdown-item editBtn' style='cursor:pointer;' data-id='".$d."'><i class='fas fa-edit'></i> Update</a> 
                  <a class='dropdown-item deleteBtn' style='cursor:pointer;' data-id='".$d."'><i class='fas fa-trash'></i> Remove</a>
                </div>
              </div>";
            }],
        ];

        $params = array(
            "var" => $var,
            "columns" => $columns,
            "sql" => $query  
        );
        
        return $this->processTable($params);
    }
}
