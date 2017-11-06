<?php

namespace App\Http\Controllers;
use App\Model\Sign;
use App\Model\User;
use Illuminate\Http\Request;

class SignController extends Controller
{
	//type:0表示签退，type:1表示签到
	function sign(Request $request,$type){
		if($request->now_time===null){
			$date = time();
		}else{
			$date = strtotime($request->now_time);
		}
		$status = ['签退','签到'];
		$user = session('user');
		if($type==0){
			$sign = Sign::where('u_id',$user->id)->orderBy('id', 'desc')->first();
			if($sign->count()<=0)
				return responseToJson(0,'签到报错，联系管理员','success');
			$totle = $date-$sign->s_time;
			if($totle<0||$totle>3600*6)
				return responseToJson(2,date("Y-m-d H:i:s",$sign->s_time),'success');
			$res = Sign::create(['u_id'=>$user->id,'s_time'=>$date,'s_type'=>$type,'s_totle'=>$totle]);
		}else{
			$res = Sign::create(['u_id'=>$user->id,'s_time'=>$date,'s_type'=>$type]);
		}
		if($res->count()>=1){
			User::where('id',$user->id)->update(['u_status'=>$type]);
			$data = ['u_id'=>$user->u_name,'s_time'=>date("H:i:s",$date),'s_type'=>$status[$type],'s_totle'=>$res->s_totle];
			return responseToJson(1,$data,'success');
		}else{
			return responseToJson(0,'签到报错，联系管理员','success');
		}
	}
	function get_log(){
		$status = ['签退','签到'];
		$data = Sign::where('s_time','>',strtotime('today'))->orderBy('s_time','desc')->paginate(10);
		foreach ($data as $key => $value) {
			$value->u_id = $value->user->u_name;
			$value->s_time = date("H:i:s",$value->s_time);
			$value->s_type = $status[$value->s_type];
		}
		return responseToJson(1,$data,'success');
	}
	//签到统计功能
	function sign_mag(){
    		$week = get_week();
		$data = User::signMsg($week);
		return responseToJson(1,$data,'success');
	}
	function get_sign(Request $request){
		$data = User::signMsg(strtotime($request->time[0]),strtotime($request->time[1]));
		return responseToJson(1,$data,'success');
	}
	function user_sign($id){
		$status = ['签退','签到'];
		$week = get_week();
		$data = Sign::where([['s_time','>',$week],['u_id',$id]])->orderBy('s_time','desc')->paginate(12);
		foreach ($data as $key => $value) {
			$value->u_id = $value->user->u_name;
			$value->s_time = date("Y-m-d H:i",$value->s_time);
			$value->s_type = $status[$value->s_type];
		}
		return responseToJson(1,$data,'success');
    	}
}
