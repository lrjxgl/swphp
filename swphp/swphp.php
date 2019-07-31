<?php
namespace Swphp;
class Swphp
{
	public static $maxid=0;
	public $request;
	public $response;
	public static  $instance;
	public $testnum=0;
	public function __construct($request,$response){
		$this->response=$response;
		$this->request=$request;
		self::$instance=$this;
	}
	static public function getinstance(){
	    if(!self::$instance) self::$instance = new self();
	    return self::$instance;
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
	
	public function run(){
		$request=$this->request;
		$response=$this->response;
		$path=$request->server["request_uri"];
		$m=$this->_get("m");
		if(!$m){
			$m="index";
		}
		$a="index";
		$a=$this->_get("a");
		if(!$a){
			$a="index";
		}
		$ex=explode("_",$m);
		$module=$ex[0];	
		switch($path){
			
			case "/admin.php":
				$mClass="App\admin\\".$m."Control";
				break;
			case "/module.php":
				$mClass="App\\".$module."\index\\".$m."Control";
				break;
			case "/moduleadmin.php":
				$mClass="App\\".$module."\admin\\".$m."Control";
				break;
			case "/favicon.ico":
			self::error("favico.ico");
				break;
			default:
				$mClass="App\index\\".$m."Control";
				$viewConfig["template_dir"]=ROOT_PATH."views/default/index";
				break;		
		}
		
		if(!\class_exists($mClass)){
			return self::error("error $mClass,$aFun no exists ");
		}
		$cls=new $mClass($request,$response);
		$cls->setView($viewConfig);
		$aFun="on".$a;
		if(!method_exists($mClass,$aFun)){
			return self::error("error $mClass,$aFun no exists ");
		}
		if(\method_exists($mClass,"init")){
			$cls->init();
		}
		$result=$cls->$aFun();
		if($result!==false){
			self::display($result);
		}
		
	}
	public function write($data){
		$this->response->write($data);
	}
	public  function error($message){
		$this->response->status("500");
		$this->response->header("Content-Type", "text/html;charset=utf8;");
		
		try{
			throw new SException($message);
		}catch(SException $e){
			
			$this->response->end($message."<br/>".$e->getMessage());
		}
		throw new SException("exit");
	}
	
	public function display($result){
		 
		if(empty($result)){
			$$result="\n";
		}
		$this->response->header("Content-Type", "text/html;charset=utf8;");
		if(is_array($result)){
			$result=json_encode($result);
		}
		$this->response->end($result);
	}
	
}
