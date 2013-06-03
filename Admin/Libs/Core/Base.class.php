<?php
abstract class Base{
	/*public function getRequestType(){
		if( isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ){
			return 'AJAX';
		} else {
			return $_SERVER['REQUEST_METHOD'];
		}
	}
	
	public function isPost(){
		return $this->getRequestType()=='POST';
	}
	public function isAjax(){
		return $this->getRequestType()=='AJAX';
	}
	*/
}
?>