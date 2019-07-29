<?php

session_start();
loadAllFiles();
function loadAllFiles(){
	loadFile("swphp");
	loadFile("app");
}
function loadFile($dir){
	$op=opendir($dir);
	while(false!==$file=readdir($op)){
		if($file!="." && $file!=".."){
			if(is_file($dir."/".$file)){
				require_once $dir."/".$file;
			}elseif(is_dir($dir."/".$file)){
				loadFile($dir."/".$file);
			}
		}
	}
}
$checkfiles=array();
function getFiles($dir){
	global $checkfiles;
	$op=opendir($dir);
	while(false!==$file=readdir($op)){
		if($file!="." && $file!=".."){
			if(is_file($dir."/".$file)){
				$checkfiles[]=$dir."/".$file;
			}elseif(is_dir($dir."/".$file)){
				getFiles($dir."/".$file);
			}
		}
	}
}

function getAllFiles(){
	getFiles("app");
	getFiles("swphp");
}
function checkFiles(){
	global $checkfiles;
	getAllFiles();
	$news=array();
	foreach( $checkfiles as $file){
		$news[$file]=date("H:i:s",filemtime($file));
	}
	if(!isset($_SESSION["checkfiles"])){
		$_SESSION["checkfiles"]=$news;
		return false;
	}
	
	$olds=$_SESSION["checkfiles"];
 
	$_SESSION["checkfiles"]=$news;
	 
	if(!empty($olds)){
		
		foreach($news as $k=>$v){
			
			if($v!=$olds[$k]){
				return true;
			}
		}
		return false;
	}else{
		return false;
	}
	
	
}
file_put_contents("start.lock",0);
define("ROOT_PATH",  str_replace("\\", "/", dirname(__FILE__))."/");
$http = new swoole_http_server("0.0.0.0", 9501);
$http->set(array(
    'upload_tmp_dir' => ROOT_PATH."/temp/upload",
	'document_root' => ROOT_PATH."/document_root",
	
	'enable_static_handler' => true,
));
$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
	Swoole\Timer::tick(1000,function() use($server){
		$res=checkFiles();
 
		if($res){
			if(file_exists("start.lock")){
				unlink("start.lock");
			}
			posix_kill(posix_getpid(),9);	
		}
	});
});

$http->on("request", function ($request, $response) {
	
	Swphp\Swphp::run($request,$response);
});

$http->start();