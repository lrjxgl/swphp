<?php
namespace App\B2b\index;
class b2bControl extends \Swphp\Control{
	public function onIndex(){
		$list=\Swphp\MM("b2b","b2b_product")->select(array(
			"limit"=>10
		));
		$this->view->assign(array(
			"list"=>$list
		));
	}
}