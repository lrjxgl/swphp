<?php
namespace Swphp;	
class View{
	
	public $template_dir   = '';//模版文件夹
    public $cache_dir      = '';//缓存文件夹
    public $compile_dir    = '';//编译文件夹
	public $html_dir		='';//生成静态文件夹
	public $html_file="";
	public $_var;
	public $request;
	public $response;
	public function __construct($request,$response){
		$this->request=$request;
		$this->response=$response;
	}
	
	public  function goAssign($tpl_var, $value = ''){
		
		if($this->request->get["ajax"]){
			$data=json_encode(array(
				"error"=>0,
				"message"=>"success",
				"data"=>$tpl_var
			));
			$this->response->end($data);
			sExit();
		}else{
			$this->assign($tpl_var,$value);
		}
	}
	 public function assign($tpl_var, $value = '')
    {
		 
        if (is_array($tpl_var))
        {
            foreach ($tpl_var AS $key => $val)
            {
                if ($key != '')
                {
                    $this->_var[$key] = $val;
                }
            }
        }
        else
        {
            if ($tpl_var != '')
            {
               $this->_var[$tpl_var] = $value;
            }
        }
    }
	public function display($filename, $cache_id = ''){
		$filename.=".php";  
		$out =$this->fetch($filename, $cache_id);
		if($this->html_file){
			umkdir(dirname($this->html_file));		
			file_put_contents($this->html_file,$out);
		}
		return $out; 		
	}
	
	public  function html($htmlfile,$expire=3600){
		$file=$this->html_dir."/".$htmlfile;
		$filestat = @stat($filename);
		if(file_exists($file) && !isset($_GET['forceHtml']) && $filestat['mtime']>time()-$expire){
			return false;
		}else{
			$this->html_file=$file;
		}
		
	}
	
	public   function fetchhtml($str){
		return $str; 
	}
	
	public function fetch($filename, $cache_id = '',$dir=""){
		 
		ob_start();
		if(!empty($this->_var)){
			extract($this->_var);
		}
		$tpl=$this->template_dir."/".$filename;
		if(!file_exists($tpl)) {
			return Swphp::getinstance()->error($tpl."模板不存在");
			
		}
		require $tpl;
		$out=ob_get_contents();
		ob_end_clean();
		return $out;
	}
	
	public   function inc($filename){
		if(!file_exists($this->template_dir."/".$filename)){
			return Swphp::getinstance()->error($filename."模板不存在");
		}  
		if(!empty($this->_var)){
			extract($this->_var);
		}
		 
		require $this->template_dir."/".$filename;
	}
	
	public  function is_cached($filename, $cache_id = ''){
		return true;
	} 
	
}
?>