<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Sign extends Model
{
	protected $table = 'sign';
    	public $timestamps = false;
	protected $fillable = [
	        	'u_id', 's_time','s_end','s_totle','s_type'
	];
	public function user(){
	        	return $this->belongsTo('App\Model\User','u_id');
	}
	public function getSTotleAttribute($value){
		return time_diff($value);
	}
}
