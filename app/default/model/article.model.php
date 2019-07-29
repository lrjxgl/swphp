<?php
namespace Model;
class Article extends \Swphp\Model{
	public $table="article";
	public function test(){
		return $this->getRow("select * from ".\Swphp\table("article")." limit 1");
	}
	
}