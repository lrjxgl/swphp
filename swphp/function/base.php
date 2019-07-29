<?php
function skyLog($file,$content){
	$file=ROOT_PATH."temp/log/".$file;
	umkdir(ROOT_PATH."temp/log/");
	if(file_exists($file)){
		if(filesize($file)>1024*1024*300){
			rename($file,ROOT_PATH."temp/log/".str_replace(".",date("Ymdhis").".",basename($file)));
		}
		clearstatcache() ;
	}
	
	$fp=fopen($file,"a+");
	
	fwrite($fp,"\r\n---".date("Y-m-d H:i:s")."--".$_SERVER['REQUEST_URI']."--\r\n".$content."\r\n");
	fclose($fp);	
}