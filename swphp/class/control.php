<?php
namespace Swphp;
class Control{
	public $request;
	public $response;
	public $view;
	public $template_dir;
	public function __construct($request,$response){
		$this->request=$request;
		$this->response=$response;
		
	}
	public function setView($config){
		$this->view=new View($this->request,$this->response);
		$this->view->template_dir=$config["template_dir"];
	}
	public function _get($k){
		if(isset($this->request->get[$k])){
			return $this->request->get[$k];
		}
	}
	public function _post($k){
		if(isset($this->request->post[$k])){
			return $this->request->post[$k];
		}
	}
	public function _cookie($k){
		if(isset($this->request->cookie[$k])){
			return $this->request->cookie[$k];
		}
	}
	public function _files(){
		return $this->request->files;
	}
	public function _header($k,$v){
		return $this->response->header($k,$v);
	}
	
	public function _end($data){
		return $this->response->end($data);
	}
	public function goAll($message,$error=0,$data=array(),$url="/"){
		$result=array(
			"error"=>$error,
			"message"=>$message,
			"data"=>$data
		);
		if($this->_get("ajax")){
			return $result;
		}else{
			return $this->view->display("goall");
		}
		
	}
}