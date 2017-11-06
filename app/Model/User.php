<?php
namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	public $role = ['用户','管理员','超级管理员'];
	public $sex = ['女','男'];
	public static $status = ['离线','在线'];
	protected $table = 'user';
	public $timestamps = false;
	protected $fillable = [
	        'u_name', 'u_number','u_sex','u_class','u_phone','u_status','u_role'
	];
	public function getURoleAttribute($value)
	{
	        return $this->role[$value];
	}
	public function getUSexAttribute($value)
	{
	        return $this->sex[$value];
	}
	public function getUStatusAttribute($value)
	{
	        return self::$status[$value];
	}
	public function sign()
    	{
        		return $this->hasMany('App\Model\Sign','u_id','id');
    	}
    	public static function signMsg($time,$end=0){
    		if($end!=0)
    			$totle = DB::table('sign')->where([['s_time','>',$time],['s_time','<',$end]])->select(DB::raw('SUM(s_totle) as totle'),'u_id')->groupBy('u_id')->get();
    		else
    			$totle = DB::table('sign')->where([['s_time','>',$time]])->select(DB::raw('SUM(s_totle) as totle'),'u_id')->groupBy('u_id')->get();
    		$user = DB::table('user')->where('u_role','<>','2')->select('u_name','id','u_status','u_number')->get();
    		foreach ($user as $key => $value) {
    			foreach ($totle as $val) {
    				if($value->id==$val->u_id)
    					$value->totle = time_diff($val->totle);
    			}
    			if(!isset($value->totle))
    				$value->totle = 0;
    			$value->u_status = self::$status[$value->u_status];
    		}
    		return $user;
    	}
}
