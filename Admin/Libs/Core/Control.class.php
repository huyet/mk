<?php
abstract class Control extends Base{
	
	public $assign=array();
	
	protected function _init(){	
	}
	
	public function __construct(){
		$this->_init();
		$this->assign[TEMPLATE_NAME] = '/Module/'.MODULE.'/'.CONTROL.'_'.ucfirst(METHOD).'.html';
	}
	public function __destruct(){
	}
	
	public function getAssign(){
		return $this->assign;
	}
}
?>