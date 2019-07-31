<?php
namespace App\index;
use \Swphp\View;
use \Swphp\library\upload;
class UploadControl extends \Swphp\Control{
	public function onIndex(){
		$up=new Upload();
		$_FILES=$this->request->files;
		$res=$up->uploadfile("upimg");
		return '<img src="'.$res["filename"].'">';
	}
}
?>