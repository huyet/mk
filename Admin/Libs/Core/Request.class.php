<?php
class Request{
	private $_acceptType=array('html','amf');
	private $_requestType;
	
	public function __construct($requestType='html'){
		$this->_requestType = $requestType;
	}
	
	public function setMDMH(){
		switch ($this->_requestType){
			case 'html':$this->htmlEnvInit();break;
			case 'amf':$this->amfEnvInit();break;
			default:throw new AppException("request:{$this->_requestType} is bad request");break;
		}
	}
	
	private function htmlEnvInit(){
		define('MODULE', isset($_GET['me']) ? ucfirst( strtolower( $_GET['me'] )) : DEFAULT_MODULE );
		define('CONTROL', isset($_GET['cl']) ? ucfirst( strtolower( $_GET['cl'] )) : DEFAULT_CONTROL );
		define('METHOD', isset($_GET['md']) ? ucfirst( strtolower( $_GET['md'] )) : DEFAULT_METHOD );
	}
	
	private function amfEnvInit(){
		
	}
	
}
?>