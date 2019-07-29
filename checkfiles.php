<?php
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
		echo "unsession";
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
$res=checkFiles();
var_dump($res);