<?php
namespace Swphp;
class Session{
	public static $ssids=array();
	public static $data=array();
	public static function start(){
		self::get_session_id();
	}
	public static function get_session_id(){
		if(isset(Swphp::$request->cookie["session_id"])){
			self::update_session_expire();
			return Swphp::$request->cookie["session_id"];
		}
		$session_id=Swphp::$request->cookie["session_id"];
		Swphp::$maxid++;
		$val=time().Swphp::$maxid;
		$time=time()+3600;
		self::$ssids[$val]=$time;
		Swphp::$response->cookie("session_id",$val,$time);
		return $val;
	}
	public static function update_session_expire(){
		$time=time()+3600;
		Swphp::$response->cookie("session_id",Swphp::$request->cookie["session_id"],$time);
	}
	
	public static function set($k,$v){
		$session_id=self::get_session_id();
		self::$data[$session_id][$k]=$v;
	}
	public static function get($k){
		$session_id=self::get_session_id();
		return self::$data[$session_id][$k];
	}
}
?>