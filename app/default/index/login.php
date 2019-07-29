<?php
namespace App\index;
use \Swphp\View;
use \Swphp\session;
class LoginControl{
	
	public function onIndex(){
		
		return View::display("login/index");
	}
	public function onLoginSave(){
		$telephone=$_POST["telephone"];
		$password=$_POST["password"];
		$ssuser=\Swphp\M("user")->selectRow("telephone='".$telephone."'");
		session::set("ssuser",$ssuser);
		$redata=array(
			"error"=>0,
			"message"=>"success",
		);
		return \Swphp\Swphp::goAll("success");
	}
}