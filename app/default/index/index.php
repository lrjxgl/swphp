<?php
namespace App\index;
use \Swphp\View;
use \Swphp\session;
class IndexControl extends \Swphp\Control {
	public $session;
	public function init(){
		$this->session=new Session($this->request,$this->response);
	}
	public function onIndex(){
		
		$list=array();
		$list=\Swphp\M("article")->select(array(
			"limit"=>"10"
		));
		$test=\Swphp\M("article")->test();
		$product=\Swphp\MM("b2b","b2b_product")->select(array(
			"limit"=>"10"
		));
		$redata=array(
			"error"=>0,
			"ssuser"=>$this->session->get("ssuser"),
			"message"=>"首页展示成功了",
			"data"=>array(
				"title"=>"swphp"
			),
			"list"=>$list,
			"test"=>$test,
			"product"=>$product,
			"testnum"=>\Swphp\Dbs::$testnum
			 
		);
		$this->view->goAssign($redata);
		
		return $this->view->display("index");
		return $redata;
	}
	public function onShow(){
		
		$id=$this->_get("id");
		if(!$id){
			return $this->goAll("参数出错",1);
		}
		$obj=$this;
		\Swoole\Timer::after(5000,function() use ($obj){
			echo $obj->_get("id");
			
		});
		$data=\Swphp\M("article")->selectRow("id=".$id);
		$data["content"]=\Swphp\M("article_data")->selectOne(array(
			"where"=>"id=".$id,
			"fields"=>"content"
		));
		\Swphp\Dbs::$testnum=2;
		$this->view->assign(array(
			"data"=>$data,
			"testnum"=>\Swphp\Dbs::$testnum
		));
		return $this->view->display("show");
	}
	public function onUpImg(){
		return $this->view->display("upimg");
	}
	public function onSession(){
		$this->session->set("time",date("Y-m-d H:i:s"));
		return $this->session->get("time");
	}
	public function onGet(){
	
		return $this->session->get("time");
	}
	public function onTest(){
		\Swphp\Swphp::getinstance()->error("test exception");
		echo "hi exeption";
		
	}
}