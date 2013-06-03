<?php
class EaL{//mean Error and Log

	private static $errFile = 'myerror.log';
	
	private function __construct(){}
	
	public static function init(){
		set_error_handler(array('EaL','errorHandler'));//可以捕获E_NOTICE 、E_USER_ERROR、E_USER_WARNING、E_USER_NOTICE
		set_exception_handler(array('EaL','exceptionHandler'));
		register_shutdown_function(array('Eal','shutdownFunction'));//捕获致命错误
		
		error_reporting(E_ALL);
		ini_set('display_errors','off');//如果为on就打印错误，否则会返回500http状态码
		ini_set('log_errors','off');
		
	}
	
	public static function exceptionHandler(Exception $e){
		error_log( "\r\nUncaught exception: " . $e->getMessage()."\n", 3, self::$errFile );
	}
	
	public static function errorHandler($errno, $errstr, $errfile, $errline){
		error_log( "\r\n".'File:'.basename($errfile).' Line:'.$errline."\r\n".$errstr ,3,self::$errFile );
	}
	public static function shutdownFunction(){
		//由于这个函数是加载进内存执行，所以没有相对路径的概念，php_error.log这个在apache的主目录下
		$Msg=error_get_last();
		if(($Msg['type']&E_ERROR)||($Msg['type']&E_PARSE)||($Msg['type']&E_CORE_ERROR)||($Msg['type']&E_USER_ERROR)||($Msg['type']&E_COMPILE_ERROR)){
			error_log('PHP '.(($Msg['type']&E_ERROR)?'Fatal':'Parse').' Error :'.$Msg['message'].' on file "'.$Msg['file'].'" line '.$Msg['line'].(function_exists('apd_callstack')?'.apd_callstack---'.var_export(apd_callstack(),true):'').(function_exists('debug_backtrace')?'.debug_backtrace--'.var_export(debug_backtrace(),true):'')."\r\n",3, WWW_LOGS_PATH.'php_error.log');
		}
	}
	
	public static function logger($msg){
		trigger_error($msg);
	}
	
}

?>