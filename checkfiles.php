<?php
namespace Reload;
class CheckFile{
	public static $dirs=array("app","swphp");
	public static $checkfiles=array();
	public static $olds=array();
	public static function getFiles($dir){
		$op=opendir($dir);
		while(false!==$file=readdir($op)){
			if($file!="." && $file!=".."){
				if(is_file($dir."/".$file)){
					self::$checkfiles[]=$dir."/".$file;
				}elseif(is_dir($dir."/".$file)){
					self::getFiles($dir."/".$file);
				}
			}
		}
	}
	public static function getAllFiles(){
		foreach(self::$dirs as $dir){
			self::getFiles($dir);
		}
	}
	public static function checkFiles(){
		 
		self::getAllFiles();
		$news=array();
		foreach( self::$checkfiles as $file){
			$news[$file]=date("H:i:s",filemtime($file));
		}
		if(empty(self::$olds)){
			self::$olds=$news;
			return false;
		}
		$olds=self::$olds;
		self::$olds=$news;
		 
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

}