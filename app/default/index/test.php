<?php
namespace App\index;
class TestControl extends \Swphp\Control{
	public function onIndex(){
		return array(
			"title"=>"测试swoole框架性能",
			"content"=>"测试框架内容..."
		);
	}
}