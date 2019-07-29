<?php
namespace App\index;
use \Swphp\View;
class IndexControl {
	
	public function onIndex(){
		$list=array();
		$list=\Swphp\M("mod_b2b_product")->select(array(
			"limit"=>"10"
		));
		$test=\Swphp\M("article")->test();
		$product=\Swphp\MM("b2b","b2b_product")->select(array(
			"limit"=>"10"
		));
		$redata=array(
			"error"=>0,
			"message"=>"首页展示成功了",
			"data"=>array(
				"title"=>"swphp"
			),
			"list"=>$list,
			"test"=>$test,
			"product"=>$product
			 
		);
		View::assign($redata);
		return View::display("index");
		return $redata;
	}
	public function onShow(){
		$id=$_GET["id"];
		$data=\Swphp\M("mod_b2b_product")->selectRow("id=".$id);
		View::assign(array(
			"data"=>$data
		));
		return View::display("show");
	}
	public function onSession(){
		\Swphp\Session::set("time",date("Y-m-d H:i:s"));
		return \Swphp\Session::get("time");
	}
	public function onGet(){
		return \Swphp\Session::get("time");
	}
}