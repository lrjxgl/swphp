<?php
namespace Swphp;	
class View{
	
	public static $template_dir   = '';//模版文件夹
    public static $cache_dir      = '';//缓存文件夹
    public static $compile_dir    = '';//编译文件夹
	public static $html_dir		='';//生成静态文件夹
	public static $html_file="";
	public static $_var;
	public function __construct(){
		
	}
	
	public static function goAssign($tpl_var, $value = ''){
		
		if(get('ajax')){
			
			C()->goAll("success",0,$tpl_var);
		}else{
			self::assign($tpl_var,$value);
		}
	}
	 public static function assign($tpl_var, $value = '')
    {
		 
        if (is_array($tpl_var))
        {
            foreach ($tpl_var AS $key => $val)
            {
                if ($key != '')
                {
                    self::$_var[$key] = $val;
                }
            }
        }
        else
        {
            if ($tpl_var != '')
            {
               self::$_var[$tpl_var] = $value;
            }
        }
    }
	public static function display($filename, $cache_id = ''){
		$filename.=".php";  
		$out =self::fetch($filename, $cache_id);
		if(self::$html_file){
			umkdir(dirname(self::$html_file));		
			file_put_contents(self::$html_file,$out);
		}
		return $out; 		
	}
	
	public static function html($htmlfile,$expire=3600){
		$file=self::$html_dir."/".$htmlfile;
		$filestat = @stat($filename);
		if(file_exists($file) && !isset($_GET['forceHtml']) && $filestat['mtime']>time()-$expire){
			return false;
		}else{
			self::$html_file=$file;
		}
		
	}
	
	public static function fetchhtml($str){
		return $str; 
	}
	
	public static function fetch($filename, $cache_id = '',$dir=""){
		 
		ob_start();
		if(!empty(self::$_var)){
			extract(self::$_var);
		}
		$tpl=self::$template_dir."/".$filename;
		if(!file_exists($tpl))  Swphp::error($tpl."模板不存在");
		require $tpl;
		$out=ob_get_contents();
		ob_end_clean();
		return $out;
	}
	
	public static function inc($filename){
		if(!file_exists(self::$template_dir."/".$filename))  exit($filename."模板不存在");
		if(!empty(self::$_var)){
			extract(self::$_var);
		}
		 
		require self::$template_dir."/".$filename;
	}
	
	public static function is_cached($filename, $cache_id = ''){
		return true;
	} 
	
}
?>