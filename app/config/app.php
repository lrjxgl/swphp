<?php
namespace Config;
class App{
	public static $upload_tmp_dir="/temp/upload";
	public static $document_root="/document_root";
	public static $enable_static_handler=true;
}
App::$upload_tmp_dir=ROOT_PATH.App::$upload_tmp_dir;
App::$document_root=ROOT_PATH.App::$document_root;
