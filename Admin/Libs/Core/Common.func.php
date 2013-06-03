<?php
function SlashesControl($Value){
	static $IsOpen=null;
	if($IsOpen===null) $IsOpen = get_magic_quotes_gpc();
	if( $IsOpen ){
		if(is_array($Value)){
			foreach ($Value as $k=>$v){
				$Value[$k] = SlashesControl($v);
			}
		} else {
			$Value = stripslashes($Value);
		}
	}
	return $Value;
}

function ErrorHanlder($msg,$FileName=''){
	$Allmsg = 'SCRIPT_NAME:'.$_SERVER['SCRIPT_NAME']."\r\nQUERY_STRING:".$_SERVER['QUERY_STRING']."\r\n";
	$Allmsg .= 'ERRINFO:'.$msg . ' at '.date('Y-m-d H:i:s',time() ). "\r\n";
		
	if(DEBUG){
		echo $Allmsg;exit;
	} else {
		error_log( $Allmsg , 3, LOGS_PATH.'/'.$FileName);
		if(MODULE=='Index' && CONTROL=='Index' && METHOD=='index'){
			echo '程序出错';
			exit;
		}
		if (! headers_sent ()) {
			header ( 'refresh:5;url=' . HOSTDIR );
			echo '程序出错，5秒后跳转到首页';
			exit ();
		} else {
			$str = "<meta http-equiv='Refresh' content='5;URL=" . HOSTDIR . "'>";
			exit ( $str );
		}
		
	}
}
?>