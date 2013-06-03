<?php
include_once(EXTEND_PATH.'/Smarty/Smarty.class.php');
class SmartyView extends Smarty {
	private $displayTemplate='';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function _createSmarty( $assign ){
		$this->setTemplateDir( WWW.'/Admin/Tpl/Module/Index' );
		$this->setCompileDir( WWW.'/Runtime/templates_c' );
		$this->left_delimiter = '<!--{';
		$this->right_delimiter = '}-->';
		
		$this->setDisplayTemplate($assign);
		foreach ($assign as $k=>$v){
			$this->assign($k,$v);
		}
	}
	
	private function setDisplayTemplate($assign){
		if( isset($assign[ TEMPLATE_NAME ]) && $assign[ TEMPLATE_NAME ] !='' ){
			$this->displayTemplate = $assign[ TEMPLATE_NAME ];
			unset( $assign[ TEMPLATE_NAME ] );
		}
		if( !$this->displayTemplate ){
			throw new AppException('Template:'.MODULE.'/'.CONTROL.':'.METHOD.' not appoint template');
		}
		if( !file_exists( TPL_PATH.$this->displayTemplate ) ){
			throw new AppException('Template:'.$this->displayTemplate.' not found');
		}
	}
	
	public function display(){
		parent::display($this->displayTemplate);
	}
}
?>