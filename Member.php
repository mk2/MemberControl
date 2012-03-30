<?php
//====================================================================
//色々設定とか。全部GLOBAL変数

//表示したいデータ。名前は表示されるのがデフォルトです。
$dispData = array('position','grade','faculty','section','description');

//$templateは登録データもしくは検索時の条件に使う
//facultyには各学部を、sectionには各部を、gradeは基本的にこのままで。
$template = array(
				'position'=>array('局長','部長','会計'),
				'faculty' => array('数理情報科学科','化学科','数学科','応用物理学科','応用化学科','物理学科','工業化学科','建築科','電気工学科','経営工学科','機械工学科'),
				'section' => array('ウェブ部','プログラミング部','自作部','DTP部','CG部','MIDI部'),
				'grade' => array('学部1年','学部2年','学部3年','学部4年','OB')
				);

//$titleまんまです。
$title = '部員リスト';

//$passEncodeKeyはcrypt関数でパスワードを暗号化するときのみ使用
$passEncodeKey = null;

//$passToWriteまんまです。書き込むためのパスワード。
$passToWrite = 'hogehoge';

//$topLink トップページに表示させたいリンクを連想配列で代入してください。
$topLink = array('HOME'=>'../../index.php','ANY LINK'=>'(URL)');

//その他
$postData;
$selfPHP = $_SERVER['PHP_SELF'];
//====================================================================

require_once('ManipulateXML.php');
require_once('DispHTML.php');
require_once('ValidateData.php');

//validateします。詳しくはValidate.php
$postData = Validate($_POST);
//$postDataを用いて色々処理してnodeListをゲッツします。$postDataを参照で渡しています。詳しくはManipulateXML
$nodes = ManipulateMemberList::Execute($postData);

//以下トップページの表示
?>
<html>
<head>
	<title><?php print $title;?></title>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type"/>
	<meta content="text/javascript" http-equiv="Content-Script-Type"/>
	<meta content="text/css" http-equiv="Content-Style-Type"/>
	<link rel="stylesheet" href="c1.css" type="text/css"/>
	<link rel="stylesheet" href="c2.css" type="text/css"/>
	<script type="text/javascript" src="./javascript/DispComment.js"></script>
	<meta content="メンバー管理" name="description"/>
</head>
<body id="bodyid">
<a name="TopofPage"></a>
<div id="page">
<div id="page2">
<div id="banner">
<h1><?php print $title;?></h1>
</div>
<table cellspacing="0" cellpadding="0" id="menu"><tr>
<?php
foreach($topLink as $key => $value) {
	print '<td><a href="'.$value.'">'.$key.'</a></td>';
}
?>
</tr>
</table>
<div id="main">
<?php
//print '<p align="left">マネージャー: '.ManipulateMemberList::GetState().'</p>';
DispForm($postData['data']);
DispMemberList($nodes);
?>
<div id="left">
<h2>注意事項</h2>
<p>コメントにタグは書けません。</p>
<p>書き込みパスはhogehogeです。</p>
<h2>使い方</h2>
<p>名前と付加情報フォームで検索ができます。名前は全部一致です。一部分とかは無理です。</p>
<p>登録するときは名前と付加情報を記入して、その下のコメント欄に何か入力して、追加うんぬんボタンをクリックして登録してください。</p>
<p>データを変更するとき、元データがフォーム上に表示されます。表示された元データをいじってまた追加うんぬんボタンをクリックして登録してください。</p>
<p>変更を適用するときは必ずもう一度書き込み用パスワードを入力してください。</p>
<p>書き込み用パスワードはあらかじめ決まっています(上の注意事項参照)。削除/変更用パスワードは自分で決めてください。</p>
</div>
</div>
<div id="copy">
Copyright(C) 2007 かぐちょ,All rights reserved.なんつって
</div>
</div>
</div>
</body>
</html>