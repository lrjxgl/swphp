<?php
namespace B2b\Model;
class b2b_product extends \Swphp\Model{
	public $table="mod_b2b_product";
	public function test(){
		return $this->getRow("select * from ".\Swphp\table("article")." limit 1");
	}
	
}