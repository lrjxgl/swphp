<?php
namespace Swphp;

class DB
{
	public $db;
	public	$dbconfig=array();//配置
	public	$charset="utf8mb4";//编码
	public	$testmodel=false;//测试模式 开启后将会输出错误的sql语句 
	public $base;
	public $query=NULL;//最近操作的
	public $sql;
	public $stime;
	/**
	*mysql初始化 
	*/
	 public function __construct($data=array("charset"=>"utf8mb4")){
		 
		 if(!defined("TABLE_PRE")){
			 define("TABLE_PRE","");
		 }
		 $this->charset=$data['charset'];
		 if(defined("TESTMODEL") && TESTMODEL==true){
			 $this->testmodel=true;
		 }else{
			 if(define("TESTMODEL",false));
		 }
		  
		 
	 }
	 
	 public function set($config){
		 $this->dbconfig=$config;
	 }
	 /*
	  * 连接mysql
	  * */
	 public function connect($config=array()){
		if(!empty($config)){			
			$master=$config;
			$this->dbconfig=$config;
		}else{
			$master=$this->dbconfig;
			
		}
		$arr=explode(":",$master['host']);
		$host=$arr[0];
		if(isset($arr[1])){
			$port=$arr[1];
			$master['host']=$arr[0];
		}else{
			$port=3306;
		}
		$this->db=new \mysqli($master['host'],$master['user'],$master['pwd'],$master['database'],$port);
	 	if ($this->db->connect_error) {
		    Swphp::error('Connect Error (' . $this->db->connect_errno . ') '
		            . $this->db->connect_error);
		}
		$this->db->query("SET sql_mode=''"); 
		$this->db->set_charset( $this->charset);;
		
	 }
	 /**
	  * 执行sql语句
	  */
	 public function query($sql,$param=array()){
	 	if(!$this->db){
			$this->connect();
			return $this->query($sql,$param);
	 		 
		}
	 	if(!@$this->db->ping()){
	 		$this->connect();
	 		return $this->query($sql,$param);		 
	 	}
	
		$this->sql=$sql;
		
		 
	 	if(!empty($param)){
	 				 $stmt =  $this->db->stmt_init();
	 				 $stmt->prepare($sql);
	 				 $stmt=$this->stmt_bind_param($stmt,$param);
	 				 $stmt->execute();
	 				 $this->query=$stmt->get_result();
	 	}else{
	 				$this->query=$this->db->query($sql);
	 	}
		 
		 
		 
		if($this->errno() >0 ){
			$e=$this->error();
			if(TESTMODEL){
				Swphp::getinstance()->error("sql错误：".$sql." ".$e);
				
				return false;
			}else{
				Swphp::getinstance()->error("sql错误");
				return false;
			}
		};
		return $this->query;
		 
	 }
	 public function stmt_bind_param($stmt,$params){
	 		if ($params != null) {
	 			$types = ''; 
	 			foreach($params as $param) {
	 			  if(is_int($param)) {
	 				$types .= 'i'; //integer
	 			  } elseif (is_float($param)) {
	 				$types .= 'd'; //double
	 			  } elseif (is_string($param)) {
	 				$types .= 's'; //string
	 			  } else {
	 				$types .= 'b';
	 			  }
	 			}
	 			$bind_names[] = $types;
	 			for ($i=0; $i<count($params);$i++) {
	 			  $bind_name = 'bind' . $i;
	 			  $$bind_name = $params[$i];
	 			  $bind_names[] = &$$bind_name;
	 			}
	 			call_user_func_array(array($stmt,'bind_param'),$bind_names);
	 		  }
	 		  return $stmt; 
	 }
	 public function slowLog(){
		 
	 }

	 /**
	  * 返回结果集中的数目
	  */
	public function num_rows(){
		return $this->query-> num_rows ();
	}
	
	/**
	 * 将结果集解析成数组
	 */
	public function fetch_array($result_type=MYSQLI_ASSOC){
 
		return $this->query->fetch_array($result_type);	
	}
	
	/**
	 * 从结果集中取一行
	 */
	public function fetch_row($result_type=MYSQLI_ASSOC){
		return $this->query->fetch_array($result_type);	
	}
	
	
	/**
	 * 取得结果集中字段的数目
	 */
	public function num_fields(){
		return $this->query->field_count ();
	}
	
	/*
	 * 插入数据
	 * */
	public function insert($table,$data){
		$fields=$this->compile_array($data);
		$this->query("INSERT INTO ".TABLE_PRE.$table." SET $fields ", $this->db);
		return $this->db->insert_id;
	}
	/**
	 * 更新数据
	 */
	public function update($table,$data,$w=""){
		$fields=$this->compile_array($data);
		$where=$w?" WHERE ".$this->compile_array($w," AND"):"";
		$this->query("UPDATE ".TABLE_PRE.$table." SET {$fields} {$where} ");
		return $this->db->affected_rows;
	}
	/**
	 * 删除数据
	 */
	public function delete($table,$w=''){
		$where=$w?" WHERE ".$this->compile_array($w," AND "):"";
		$this->query("DELETE FROM ".TABLE_PRE."$table {$where} ");
		return $this->db->affected_rows;;		
	} 
	
	/**
	 * 获取全部数据
	 *array("table","where"=>array(),"order","start","pagesize","fields")
	*/
	public function SELECT($table,$data=array(),&$rscount=false){		
		$start=isset($data['start'])?$data['start']:0;
		$pagesize=isset($data['limit'])?$data['limit']:1000000;
		$fields=isset($data['fields'])?$data['fields']:" * ";
		$where=(!isset($data['where']) or empty($data['where']))?"":" where ".$this->compile_array($data['where']," AND ");
		$order=isset($data['order']) && !empty($data['order'])?" ORDER BY ".$data['order']:"";
		if($rscount){
			$rscount=$this->getCount($table,isset($data['where'])?$data['where']:'');
		}
		$rscount=intval($rscount);
		return $this->getAll("SELECT {$fields} FROM ".TABLE_PRE."{$table}  $where {$order} LIMIT $start,$pagesize ");
		
	}
	/**
	 * 获取一个数据
	 *array("table","where","order","start","pagesize","fields")
	*/
	public function selectOne($table,$data=array()){
		$start=isset($data['start'])?$data['start']:0;
		$pagesize=isset($data['limit'])?$data['limit']:1;
		$fields=isset($data['fields'])?$data['fields']:" * ";
		$where=(!isset($data['where']) or empty($data['where']))?"":" where ".$this->compile_array($data['where']," AND ");
		$order=isset($data['order']) && !empty($data['order'])?" ORDER BY ".$data['order']:"";
		return $this->getOne("SELECT {$fields} FROM ".TABLE_PRE."{$table}  {$where} {$order} LIMIT {$start},1 ");
	}
	/**
	 * 获取一行数据
	 *array("table","where","order","start","pagesize","fields")
	*/
	public function selectRow($table,$data=array()){
		$start=isset($data['start'])?$data['start']:0;
		$pagesize=isset($data['limit'])?$data['limit']:1;
		$fields=isset($data['fields'])?$data['fields']:" * ";
		$where=(!isset($data['where']) or empty($data['where']))?"":" where ".$this->compile_array($data['where']," AND ");
		$order=isset($data['order']) && !empty($data['order'])?" ORDER BY ".$data['order']:"";
		return $this->getRow("SELECT {$fields} FROM ".TABLE_PRE."{$table}  {$where} {$order} LIMIT {$start},1 ");
	}
	/**
	 * 获取一列数据
	 *array("table","where","order","start","pagesize","fields")
	*/	
	public function selectCols($table,$data=array(),&$rscount=false){
		$start=isset($data['start'])?$data['start']:0;
		$pagesize=isset($data['limit'])?$data['limit']:1000000;
		$fields=isset($data['fields'])?$data['fields']:" * ";
		$where=(!isset($data['where']) or empty($data['where']))?"":" where ".$this->compile_array($data['where']," AND ");
		$order=isset($data['order']) && !empty($data['order'])?" ORDER BY ".$data['order']:"";
		if($rscount){
			$rscount=$this->getCount($table,isset($data['where'])?$data['where']:'');
		}
		$rscount=intval($rscount);
		return $this->getCols("SELECT {$fields} FROM ".TABLE_PRE."{$table}  {$where} {$order} LIMIT $start,$pagesize ");
	}
	
	
	
	/**
	 * 获取统计数据
	 */	
	public function getCount($table,$w=array()){
		
		$where=empty($w)?"":" where ".$this->compile_array($w," AND ");
		return $this->getOne("SELECT COUNT(*) FROM ".TABLE_PRE.$table." $where ");
	}
	/**
	 * 获取全部数据
	 */
	public function getAll($sql,$param=array()){
		$res=$this->query($sql,$param);
		if(!$res){
			return false;
		}
		$data=array();
		if($res!==false)
		{
			while($rs=$res->fetch_array(MYSQLI_ASSOC)){
				$data[]=$rs;
			}
			 
			$this->slowLog();
			return $data;
		}else
		 {
			return false;
			
		}
	}

	/**
	 * 获取一个字段
	 */
	public function getOne($sql,$param=array()){
		$res=$this->query($sql,$param);
		if(!$res){
			return false;
		}
		if($res !==false){
			$rs=$res->fetch_array(MYSQLI_ASSOC);
			 
			$this->slowLog();
			if($rs!=false){
				 
				return array_shift($rs);
			}else{
				return '';
			}
		}
		else {
			return false;
		}
		
	}
		
	/*获取一行*/
	 public function getRow($sql,$param=array()){
        $res = $this->query($sql,$param);
        if(!$res){
        	return false;
        }
        if ($res !== false){
		 	
			$arr=$res->fetch_array(MYSQLI_ASSOC);
			$this->slowLog();
            return $arr;
        }else{
            return false;
        }
    }
    /*获取一列*/
    public function getCols($sql,$param=array())
	{
		$res=$this->query($sql,$param);
		if(!$res){
			return false;
		}
		$data=array();
		if($res!==false){
			while($rs=$res->fetch_array(MYSQLI_ASSOC)){
				$data[]=array_shift($rs);
			}
			
			$this->slowLog();
			return $data;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取影响的行数
	 */
	public function affected_rows(){
	 
		return  $this->query -> affected_rows;
	}
	/*
	 * 最新插入的函数
	 * */
	public function insert_id( ){
        return $this->db->insert_id;
    }
	
	/*复制表*/
	public function copy_table($table,$newtable){
		
		$rs=$this->getRow("show create table ".TABLE_PRE."$table ");
		 
		$sql=preg_replace("/CREATE TABLE/i","CREATE TABLE IF NOT EXISTS",$rs['Create Table'],1);
		$sql=str_replace(TABLE_PRE.$table,TABLE_PRE.$newtable,$sql);
		$this->query($sql);
		return true;
	}
	
    /*
     * 获取错误信息
     * */
    public function error(){
        return $this->db->error;
    }
	/*
	 * 获取错误代号
	 * */
    public function errno(){
        return $this->db->errno;
    }
	
	
	 /*
	  * 判断是否查询语句
	  * */
	 public function isselect($sql){ 		
		preg_match("/^[ ]*(select).*/i",$sql,$a);
		if($a)
		{
			return true;
		}else{
			return false;
		}
 
	 }
	/*字符串转义*/ 
	public function newaddslashes($value){
		if(empty($value)){
			return $value;
		}else{
			if(is_array($value)){
				 return array_map('newaddslashes', $value);
			}else{				
				$value=stripslashes($value);
				$value=str_replace("\'","'",$value);  
				$value=str_replace('\"','"',$value);
				//$value=addslashes(trim($value));
				$value=$this->db->real_escape_string($value);
				return $value;	
			}
		}	 
	}
	
	public function compile_array($data,$d=","){
		
		$dot=$fields="";
		$i=0;
		if(is_array($data)){ 
			foreach($data as $k=>$v){
				if($i>0) $dot=$d;
				if(preg_match("/[<|>]/",$k)){
					$fields.="$dot {$k}'".$this->newaddslashes($v)."' ";
				}else{
					$fields.="$dot $k='".$this->newaddslashes($v)."' ";
				}
				$i++;	
			}
			return $fields;
		}else{
			return $data;
		}
	}
	
	public function __destruct(){
		 $this->close();
	}
	
	public function close(){
		$this->db=NULL;
	}
	/*生成md5缓存的key*/
	public function md5key($sql){
		$sql=strtolower($sql);
		$key=md5($sql);
		preg_match("/from (.*) [where]?/is",$sql,$data);
		$table=trim($data[1]);
		return "sql".$table."_".$key;
	}
	 

}	

?>