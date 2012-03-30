<?php
//検索条件を変更するためのFormの表示を行う関数
function DispForm($defaultData = null)
{
	$template = $GLOBALS['template'];
	$toDoFile = $GLOBALS['selfPHP'];
?>
<form method="POST" action="<?php print $toDoFile; ?>">
<table align="center">
<tr align="right">
<td>
名前:&nbsp;<input type="text" name="name" value="<?php print $defaultData['name'];?>" size="12"/>
&nbsp;&nbsp;
付加情報: 
<?php
foreach($template as $key => $values)
{
	print '&nbsp;<select name="'.$key.'">';
	print '<option value="">'.$key.'</option>';
	foreach($values as $value)
	{
		$out = '<option value="'.$value.'"';
		if($value == $defaultData[$key]) {
			$out .= ' selected="selected"';
		}
		$out .= ' >'.$value.'</selected>';
		print $out;
	}
	print '</select>';
}
?>
</td>
</tr>
<tr align="right">
<td>
<input type="submit" name="reset" value="表示をリセットする"/>
<input type="submit" name="refresh" value="表示を変更する"/>
</td>
</tr>
<tr align="right">
<td>
<textarea name="description" rows="5" cols="60"><?php print $defaultData['description'];?></textarea>
</td>
</tr>
<tr align="right">
<td>
書き込み用パスワード:&nbsp;
<input type="password" name="writePassword" size="12"/>
&nbsp;&nbsp;
削除/変更用パスワード:&nbsp;
<input type="password" name="deletePassword" size="12" value="<?php print $defaultData['password'];?>"/>
</td>
</tr>
<tr align="right">
<td>
<input type="submit" name="add" value="追加/変更の適用"/>
</td>
</tr>
</table>
</form>
<hr/>
<?php
}
//$memberList表示するための関数
//$memberListはnodeList形式で与える
function DispMemberList($memberList)
{
	$toDoFile = $GLOBALS['selfPHP'];
	$dispData = $GLOBALS['dispData'];
	print '<div id="right">';
	foreach($memberList as $member) {
		print '<h2>'.$member->getAttribute('name').'</h2>';
		foreach($dispData as $data) {
			switch($data) {
				case 'description':
					$content = htmlentities($member->textContent,ENT_QUOTES,'utf-8');
					if(strlen($content) > 50) {
						$content = '<span ondblClick="showComment(this)">'.substr($content,0,69).'......DoubleClick<h5 class="comment">全文(ダブルクリックで閉じます): '.$content.'</h5></span>';
					}
					$out .= '<p>コメント: '.$content.'</p>';
					break;
			  	default:
			  		$content = $member->getAttribute($data);
			  		$out .= '<p>'.$content.'</p>';
			  		break;
			  }
		}
		$out .= '<form method="POST" action="'.$toDoFile.'"><p align="right">削除/変更用パスワード: <input type="password" name="deletePassword" size="12"/><input type="hidden" name="id" value="'.$member->getAttribute('id').'"/><input type="submit" name="change" value="変更"/><input type="submit" name="delete" value="削除"/></p></form>';
		$out .= '<div id="pagetop"><img src="img/pagetop.png" border="0" width="11" height="9" alt="PageTop"><a href="#TopofPage">ページトップへ</a></div>';
		print $out;
		$out = null;
	}
?>
</div>
<?php
}

?>