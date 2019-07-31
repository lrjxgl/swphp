<?php
namespace App\index;
use \Swphp\View;
use \Swphp\session;
class LoginControl extends \Swphp\Control{
	public $session;
	public function init(){
		$this->session=new Session($this->request,$this->response);
	}
	public function onIndex(){
		
		return $this->view->display("login/index");
	}
	public function onLoginSave(){
		$telephone=$this->_post("telephone");
		$password=$this->_post("password");
		$yzm=$this->_post("yzm");
		$checkcode=$this->session->get("checkcode");
		if($yzm!=$checkcode){
			return $this->goAll("验证码出错 $yzm!=$checkcode ",1);
		}
		$ssuser=\Swphp\M("user")->selectRow("telephone='".$telephone."'");
		$this->session->set("ssuser",$ssuser);
		$redata=array(
			"error"=>0,
			"message"=>"success",
		);
		return $this->goAll("success");
	}
}