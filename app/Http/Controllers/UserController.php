<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Sign;

class UserController extends Controller
{
    function register(Request $request){
    	$count = User::where('u_number',$request->u_number)->count();
    	if($count==0){
    		$user = User::create($request->all());
    		session()->put('user',$user);
    		return responseToJson(1,$user,'success');
    	}else{
    		return responseToJson(0,'学号重复，请联系管理员','error');
    	}
    }
    function auto_login($id){
    	$user = User::findOrFail($id);
    	session()->put('user',$user);
    	return responseToJson(1,$user,'success');
    }
    function login($number){
    	$user = User::where('u_number',$number)->get();
    	if($user->count()==1){
    		session()->put('user',$user[0]);
    		return responseToJson(1,$user[0],'success');
    	}else
    		return responseToJson(0,'登录失败','error');
    }
    function get_user(){
        $user = User::where('u_role','<>','2')->select('u_name','u_number','id')->get();
        if($user->count()>=0)
            return responseToJson(1,$user,'success');
        else
            return responseToJson(0,'数据库错误','error');
    }
    function user_mag(){
        $sess = session('user');
        if($sess->u_role=='超级管理员'){
            $user = User::where('u_role','<',2)->get();
        }else
            $user = User::where([['u_role','<',1],['id','<>',$sess->id]])->get();
         if($user->count()>=0)
            return responseToJson(1,$user,'success');
        else
            return responseToJson(0,'数据库错误','error');
    }
    function user_del($id){
        $res = Sign::where('u_id', $id)->delete();
        if($res>=0){
            User::destroy($id);
            return responseToJson(1,"操作成功",'success');
        }else{
            return responseToJson(0,'数据库错误','error');
        }
    }
    function user_edit($id,$type){
            $role = ['用户','管理员'];
            $res = User::where('id',$id)->update(['u_role' => $type]);
            if($res>=0){
                return responseToJson(1,$role[$type],'success');
            }else{
                return responseToJson(0,'数据库错误','error');
            }
    }
}
