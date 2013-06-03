<?php
class App_Index_Index extends Control {
	public  function _init(){
	}
	
	public function index(){
		$this->assign['a']=1;
		$this->assign['b']=2;
		$this->assign['c']=array('a'=>1,'b'=>2,'c'=>array(1,2,3));
	}
}
?>