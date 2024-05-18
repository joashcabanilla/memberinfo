<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
       'user_type',
       'name',
       'username',
       'password',
       'last_login',
       'last_ip',
       'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    function login($data){ 
        $result = array();
        $result["status"] = "success";
        $user = $this->whereRaw("BINARY username = ?", [$data->username])->first();
        if(empty($user)){
            $result["status"] = "failed";
            $result["message"] = "Incorrect username";
        }else{
            if(!empty($user->deleted_at)){
                $result["status"] = "failed";
                $result["message"] = "Your account is deactivated";
                return $result;
            }
            if(Hash::check($data->password,$user->password)){
                Auth::login($user,true);
                $user->update([
                    'last_login' => Carbon::now(),
                    'last_ip' => $data->ip()    
                ]);
            }else{
                $result["status"] = "failed";
                $result["message"] = "Incorrect password";
            }
        }
        return $result;
    }

    function userTable($data){
        $query = $this->select(
            "id",
            "user_type",
            "name",
            "deleted_at",
            "last_ip",
            "last_login"
        );

        if(!empty($data->filterSearch)){
            $search = strtoupper(str_replace('ñ', 'Ñ', $data->filterSearch));
            $query->where(function($q) use($search){
                $q->orWhereRaw("name LIKE '%".$search."%'");
            });
        }

        $query = !empty($data->filterUserType) ? $query->where("user_type", $data->filterUserType) : $query;
        return $query;
    }

    function createUpdateUser($data){
        $result = array();
        $result["status"] = "success";
        $var = (object) $data;

        if(!empty($var->id)){
            $rules = [
                'username' => ['required', 'string', 'min:5',Rule::unique('users')->ignore($var->id)],
                'name' => ['required','string','min:2'],
            ];
        }else{
            $rules = [
                'username' => ['required', 'string', 'min:5','unique:users'],
                'password' => ['required','string', 'min:5'],
                'name' => ['required','string', 'min:2'],
            ];
        }

        $validator = Validator::make($data,$rules);
        if($validator->fails()){
            $result["error"] = $validator->errors();
            $result["status"] = "failed";
        }else{
            $name = strtoupper(str_replace('ñ', 'Ñ', $var->name));
            $insertData = [
                "user_type" => $var->userType,
                "name" => $name,
                "username" => $var->username,
            ];

            if(!empty($var->password)){
                $insertData["password"] = Hash::make($var->password);
            }

            $this->updateOrCreate([
                "id" => !empty($var->id) ? $var->id : 0
            ],$insertData);
        }

        return $result;
    }

    function getUser($id = ""){
        if(!empty($id)){
            $query = $this->find($id);
        }else{
            $query = $this->get();
        }
        return $query;
    }

    function deactivateUser($id, $status = "deactivate"){
        if($status == "deactivate"){
            return $this->find($id)->update(["deleted_at" => Carbon::now()]);
        }else{
            return $this->find($id)->update(["deleted_at" => null]);
        }
    }
}
