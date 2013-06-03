<?php
class App{
	private static $request;
	private static $response;
	
	static public function Run(){
		self::_init();
		self::_initEnv();
		self::_action();
	}
	
	static private function _init(){
		header('Content-Type:text/html;charset=utf-8');
		define('RUNAPP',true);
		
		error_reporting(E_ALL);
		ini_set('display_errors','on');//如果为on就打印错误，否则会返回500http状态码
		ini_set('log_errors','off');
		
		if($_SERVER['SERVER_PORT']=='80') define('HOSTDIR','http://'.$_SERVER['HTTP_HOST']);
		if($_SERVER['SERVER_PORT']=='443') define('HOSTDIR','https://'.$_SERVER['HTTP_HOST']);
		
		set_error_handler(array('App','_errorHandler'));//可以捕获E_NOTICE 、E_USER_ERROR、E_USER_WARNING、E_USER_NOTICE
		set_exception_handler(array('App','_exceptionHandler'));
		register_shutdown_function(array('App','_shutdownFunction'));//捕获致命错误
	}
	
	public static function _errorHandler($errno, $errstr, $errfile, $errline){
		ErrorHanlder( $errstr.' ON '.basename($errfile).':'.$errline."\r\n" );
	}
	
	public static function _exceptionHandler( Exception $e ){
		if ($e instanceof DbException) {
			ErrorHanlder($e->getMessage(),'mysql_error.log');
		}
		if ($e instanceof CoreException ){
			ErrorHanlder($e->getMessage(),'php_error.log');
		}
		if ($e instanceof AppException ){
			ErrorHanlder($e->getMessage(),'php_error.log' );
		}
	}
	
	public static function _shutdownFunction(){
		$Msg=error_get_last();
		if(($Msg['type']&E_ERROR)||($Msg['type']&E_PARSE)||($Msg['type']&E_CORE_ERROR)||($Msg['type']&E_USER_ERROR)||($Msg['type']&E_COMPILE_ERROR)){
			error_log('PHP '.(($Msg['type']&E_ERROR)?'Fatal':'Parse').' Error :'.$Msg['message'].' on file "'.$Msg['file'].'" line '.$Msg['line'].(function_exists('apd_callstack')?'.apd_callstack---'.var_export(apd_callstack(),true):'').(function_exists('debug_backtrace')?'.debug_backtrace--'.var_export(debug_backtrace(),true):'')."\r\n",3, LOGS_PATH.'/php_error.log');
		}
	}
	
	static private function _initEnv(){

		include_once(WWW.'/Config/config.php');
		foreach ($BaseConfig as $ConfigName=>$ConfigValue){
			define($ConfigName,$ConfigValue);//配置文件变量全局化
		}

		include_once(CORE_PATH.'/Common.func.php');
		include_once(CORE_PATH.'/Base.class.php');
		include_once(CORE_PATH.'/Control.class.php');
		include_once(CORE_PATH.'/Request.class.php');
		include_once(CORE_PATH.'/Exception.class.php');
		include_once(CORE_PATH.'/Response.class.php');
		
		$_POST = SlashesControl($_POST);
		$_GET = SlashesControl($_GET);
		$_COOKIE = SlashesControl($_COOKIE);
		
		if(isset($_POST['GLOBAL']) || isset($_GET['GLOBAL']) || isset($_COOKIE['GLOBAL']) || isset($_FILE['GLOABL'])){
			exit('bad request');
		}
		
		if( isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/x-amf' ){
			self::$request = new Request('amf');
			self::$response = new Response('amf');
		} else {
			self::$request = new Request('html');
			if( (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest') || isset($_GET['__js']) ){
				self::$response = new Response('json');
			} else {
				self::$response = new Response('html');
			}
			
		}
		
	}
	
	public static function _action(){
		self::$request->setMDMH();//分析url或raw，给MODULE，CONTROL，METHOD赋值
		
		$ControlFile = WWW_ADMIN.'/App/'.MODULE.'/'.CONTROL.'.php';
		$ControlName = 'App_'.MODULE.'_'.CONTROL;
		$MethodName = METHOD;
		
		if( !file_exists($ControlFile) ){
			throw new CoreException( "$ControlName not found" );
		}
		include_once( $ControlFile );
		$Control = new $ControlName;
		
		if( !method_exists($ControlName, $MethodName) || !is_callable(array($Control,$MethodName))){
			throw new CoreException("$ControlName:$MethodName not found");
		}
		
		$Control->$MethodName();//call_user_func( array($ControlName,$MethodName) );//无法调用构造函数的
		$Assign = $Control->getAssign();//获取处理结果集
			
		self::$response->display($Assign);
	}
}
?>