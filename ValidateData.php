<?php
//$postDataデータをvalidateするための関数
//descriptionデータはXMLに書き込むときはそのまま。HTMLとして表示するときentityに変換する
function Validate($postData)
{
	$passToWrite = $GLOBALS['passToWrite'];
	$passEncodeKey = $GLOBALS['passEncodeKey'];
	$returnValue = array();
	if($postData['refresh']) {
		$returnValue['purpose'] = 'refresh';
	}
	if($postData['add'] && ($postData['writePassword'] == $passToWrite)) {
		$returnValue['purpose'] = 'add';
		$returnValue['data']['password'] = SetPassword($postData['deletePassword'],$passEncodeKey);
		if(strlen($postData['description']) <= 1000) {
			$returnValue['data']['description'] = $postData['description'];
		} else {
			$returnValue['purpose'] = 'error';
			$returnValue['comment'] = '入力文字数のオーバーです。';
		}
	}
	if($postData['change']) {
		$returnValue['purpose'] = 'change';
		$returnValue['data']['password'] = SetPassword($postData['deletePassword'],$passEncodeKey);
	}
	if($postData['delete']) {
		$returnValue['purpose'] = 'delete';
		if($postData['deletePassword'] &&  $passEncodeKey) {
			$returnValue['data']['password'] = crypt($postData['deletePassword'],$passEncodeKey);
		} else {
			$returnValue['data']['password'] = $postData['deletePassword'];
		}
	}
	if($postData['reset']) {
		$returnValue['purpose'] = 'reset';
		$returnValue['data'] = null;
		return $returnValue;
	}
	$exception = array('refresh','add','delete','reset','description','writePassword','deletePassword','change');
	foreach($postData as $key => $value) {
		if(!in_array($key,$exception) && $value) {
			$encodeType = mb_detect_encoding($value);
			$value = mb_convert_encoding($value,'utf-8',$encodeType);
			$value = htmlentities($value,ENT_QUOTES,'utf-8');
			$returnValue['data'][$key] = $value;
		}
	}
	return $returnValue;
}
function SetPassword($password,$passEncodeKey) {
	if($password && $passEncodeKey) {
		return crypt($password,$passEncodeKey);
	} else {
		return $password;
	}
}
?>