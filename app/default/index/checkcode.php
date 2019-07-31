<?php
namespace App\index;
use \Swphp\session;
class checkcodeControl extends \Swphp\Control{
	public $session;
	public function init(){
		$this->session=new Session($this->request,$this->response);
	}
	public function onIndex(){
		$c=new \Swphp\Library\Checkcode();
		$this->response->header("Content-Type","image/jpeg");
		$str=$c->randnum(4);
		$this->session->set("checkcode",\strtolower($str));
		$con=$c->setImg($str);
		$this->response->end($con);
		return false;
	}
}