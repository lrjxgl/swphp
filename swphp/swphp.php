<?php
namespace Swphp;
class Swphp{
	public static $maxid=0;
	public static $request;
	public static $response;
	public static function run($request,$response){
		self::$response=$response;
		self::$request=$request;
		Session::start();
		$_GET=$request->get;
		$_POST = $request->post;
		$_COOKIE = $request->cookie;
		$_FILES = $request->files;
		$path=$request->server["request_uri"];
		
		$m="index";
		if(isset($_GET["m"])){
			$m=$_GET["m"];
		}
		$a="index";
		if(isset($_GET["a"])){
			$a=$_GET["a"];
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
				View::$template_dir=ROOT_PATH."views";
				break;		
		}
		
		if(!\class_exists($mClass)){
			return self::error("error $mClass,$aFun no exists ");
		}
		$cls=new $mClass();
		$aFun="on".$a;
		if(!method_exists($mClass,$aFun)){
			return self::error("error $mClass,$aFun no exists ");
		}	
		$result=$cls->$aFun();
		self::display($result);
	}
	public function assign($data){
		
	}
	public static function error($message){
		$response=self::$response;
		$response->header("Content-Type", "text/html;charset=utf8;");
		$response->end($message);
	}
	public static function display($result){
		$response=self::$response;
		if(empty($result)){
			$$result="\n";
		}
		$response->header("Content-Type", "text/html");
		if(is_array($result)){
			$result=json_encode($result);
		}
		$response->end($result);
	}
	
}
