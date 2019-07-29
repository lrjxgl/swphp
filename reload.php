<?php
if(file_exists("start.lock")){
	unlink("start.lock");
}
class reloadSwoole{
	public static $startCommand="php index.php";
	public static $swooleServerPid=0;
	public static function run(){
		Swoole\Timer::tick(1000,function(){
				if(!file_exists("start.lock")){
					exec(self::$startCommand);
				}
			});
	}

}
reloadSwoole::run();

?>