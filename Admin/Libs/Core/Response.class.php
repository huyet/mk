<?php
class Response{
	private $_acceptType=array('html','amf','json');
	private $_responseType;
	
	public function __construct($_responseType='html'){
		$this->_responseType = $_responseType;
	}
	
	public function display($assign){
		$handler = null;
		switch ($this->_responseType){
			case 'html':
					$handler = $this->htmlHandler( $assign );
					break;
			case 'amf' :
					$handler = $this->amfHandler( $assign );
					break;
			case 'json':
					$handler = $this->jsonHandler( $assign );
					break;
			default:
				throw new AppException("response:{$this->_responseType} is bad response");
		}
	}
	
	private function htmlHandler( $assgin ){
		include_once(EXTEND_PATH.'/SmartyView.class.php');
		$Sma = new SmartyView();
		$Sma->_createSmarty($assgin);
		$Sma->display();
	}
	
	private function jsonHandler( $assign ){
		unset( $assign[TEMPLATE_NAME] );
	    echo json_encode($assign);
	}
	
	private function amfHandler(){
		
	}
}
?>