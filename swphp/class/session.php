<?php
namespace Swphp;
class Session{
	public static $maxid=0;
	public static $ssids=array();
	public static $data=array();
	public $request;
	public $response;
	public function __construct($request,$response){
		$this->request=$request;
		$this->response=$response;
	}
	public function start(){
		self::get_session_id();
	}
	public function get_session_id(){
		if(isset($this->request->cookie["session_id"])){
			self::update_session_expire();
			return $this->request->cookie["session_id"];
		}
		$session_id=$this->request->cookie["session_id"];
		self::$maxid++;
		$val=time()."-".self::$maxid;
		$time=time()+3600;
		self::$ssids[$val]=$time;
		$this->response->cookie("session_id",$val,$time);
		return $val;
	}
	public function update_session_expire(){
		$time=time()+3600;
		$this->response->cookie("session_id",$this->request->cookie["session_id"],$time);
	}
	
	public function set($k,$v){
		$session_id=self::get_session_id();
		self::$data[$session_id][$k]=$v;
	}
	public function get($k){
		$session_id=self::get_session_id();
		if(isset(self::$data[$session_id][$k])){
			return self::$data[$session_id][$k];
		}
		
	}
}
?>