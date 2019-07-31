<?php
namespace Swphp;
/*配置数据库*/
class Dbs{
	static $_DBS=array();
	static $_MDS=array();
	static $testnum=1;
}

/**处理分布式数据库**/
function in_VMDBS($table){

	if(isset(\Config\Db::$VMDBS[$table])){
		return \Config\Db::$VMDBS[$table];
	}else{
		return false;
	}
}
function setDb($table='master'){
 
	$dbConfig=\Config\Db::$config;
	if(isset(Dbs::$_DBS[$table])) return Dbs::$_DBS[$table];
	if(isset($dbConfig[$table])){
		Dbs::$_DBS[$table]=new DB();
		Dbs::$_DBS[$table]->connect($dbConfig[$table]);
		return Dbs::$_DBS[$table];
	}elseif($tb=in_VMDBS($table)){
		if(isset(Dbs::$_DBS[$tb])) return Dbs::$_DBS[$tb];
		Dbs::$_DBS[$tb]=new DB();
		Dbs::$_DBS[$tb]->connect($dbConfig[$tb]);
		return Dbs::$_DBS[$tb];
	}else{
		if(isset(Dbs::$_DBS['master'])){
			return Dbs::$_DBS['master'];
		}else{
			if(isset($dbConfig['master'])){
				Dbs::$_DBS['master']=new DB();
				Dbs::$_DBS['master']->connect($dbConfig['master']);				
				return Dbs::$_DBS['master'];
			}else{
				Swphp::error("empty".$table);
			}
		}
	}
}

function closeDb(){ 
	if(!empty(Dbs::$_DBS)){
		foreach(Dbs::$_DBS as $k=>$v){
			if(isset(Dbs::$_DBS[$k]->db)){
				Dbs::$_DBS[$k]->db=null;
			}
			Dbs::$_DBS[$k]=null;
			unset(Dbs::$_DBS[$k]);
		}
	}
	if(!empty(Dbs::$_MDS)){
		foreach(Dbs::$_MDS as $k=>$v){
			if(isset(Dbs::$_MDS[$k]->db)){
				Dbs::$_MDS[$k]->db=null;
			}
			Dbs::$_MDS[$k]=null;
			unset(Dbs::$_MDS[$k]);
		}
	}
}
function table($table){
	return TABLE_PRE.$table;
}
function M($model){
	
	if(isset(Dbs::$_MDS[$model.'Model'])){ 
		return Dbs::$_MDS[$model.'Model'];
	}
	$class="\MODEL\\".$model;
	if(\class_exists($class)){
		$md=new $class();
		$md->setDb($model);
		Dbs::$_MDS[$model.'Model']=$md;	
		return Dbs::$_MDS[$model.'Model'];
	}			 		
	$m=new Model();	
	$m->setDb($model);
	$m->table=$model;			
	Dbs::$_MDS[$model.'Model']=$m;	 
	return Dbs::$_MDS[$model.'Model'];
}
function MM($md,$model){
	if(isset(Dbs::$_MDS[$model.'MModel'])){
	 
		return Dbs::$_MDS[$model.'MModel'];
	}else{
		$class="\\".$md."\MODEL\\".$model;
		if(\class_exists($class)){	
			 
			$m=new $class();
			$m->setDb($model);
			 
			Dbs::$_MDS[$model.'MModel']=$m;		 
			return Dbs::$_MDS[$model.'MModel'];
		}else{			
			$m=new Model();
			$m->setDb($model);
			$m->table=$model;			
			Dbs::$_MDS[$model.'MModel']=$m;
			 		 
			return Dbs::$_MDS[$model.'MModel'];
		}
	}
	
}