<?php
namespace App\B2b\index;
class b2bControl{
	public function onIndex(){
		return \Swphp\MM("b2b","b2b_product")->select(array(
			"limit"=>10
		));
	}
}