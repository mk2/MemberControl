<?php
//MemberListクラス
class InitMemberList
{
	private $handle;
	private static $fileName = 'memberlist.xml';
	public function __construct()
	{
		if(!file_exists(self::FileName())) {
			$dom = new DOMDocument('1.0','utf-8');
			$root = $dom->createElement('kagucho');
			$dom->appendChild($root);
			$dom->save(self::FileName());
		}
		$this->handle = new DOMDocument();
		$this->handle->load(self::FileName());
		$this->handle->formatOutput = true;
	}
	public static function Get()
	{
		$xml = new InitMemberList();
		return $xml;
	}
	public function Handle()
	{
		return $this->handle;
	}
	public function XPath()
	{
		$xPath = new DOMXPath($this->handle);
		return $xPath;
	}
	public static function FileName()
	{
		return self::$fileName;
	}
}
//MemberList操作用クラス。すべてstatic関数
class ManipulateMemberList
{
	private static $state = '特にないです。';
	public static function GetState()
	{
		return self::$state;
	}
	public static function Execute($postData)
	{
		$purpose = $postData['purpose'];
		$data = $postData['data'];
		$glPostData =& $GLOBALS['postData'];
		switch($purpose) {
			case 'add':
				self::Add($data);
				self::$state = '情報を追加しました。';
				$glPostData['data'] = null;
				return self::Search();
			case 'delete':
				if(self::Delete($data)) {
					self::$state = '情報を削除しました。';
				} else {
					self::$state = '情報を削除しませんでした。';
				}
				$glPostData['data'] = null;
				return self::Search();
			case 'change':
				self::$state = '情報を編集してください。';
				$dispData = $GLOBALS['dispData'];
				$nodes = self::Search($data);
				foreach($nodes as $node) {
					$glPostData['data']['name'] = $node->getAttribute('name');
					foreach($dispData as $key) {
						if($key == 'description') {
							$glPostData['data'][$key] = $node->textContent;
						} else {
							$glPostData['data'][$key] = $node->getAttribute($key);
						}
					}
				}
				if(self::Delete($data)) {
					self::$state = '情報を編集してください。';
					$noData[] = 'NoData';
					return self::Search($noData);
				} else {
					self::$state = '情報が編集できません。';
					$glPostData['data'] = null;
					return self::Search();
				}
			case 'refresh':
				self::$state = '表示を更新しました。';
				return self::Search($data);
			case 'error':
				self::$state = 'エラーが発生しました：';
				self::$state .= $postData['comment'];
				return self::Search();
			default:
				self::$state = '特にないです。';
				$glPostData['data'] = null;
				return self::Search();
		}
	}
	private static function Add($data)
	{
		$xml =& InitMemberList::Get()->Handle();
		$member = $xml->createElement('member');
		$parent = $xml->documentElement;
		$parent->appendChild($member);
		
		$id = time();
		$member->setAttribute('id',$id);
		$member->setIdAttribute('id',true);
		foreach($data as $key => $value) {
			if($key == 'description') {
				$ele = $xml->createElement($key);
				$eleContents = $xml->createCDATASection($value);
				$ele->appendChild($eleContents);
				$member->appendChild($ele);
			} else {
				if(is_array($value)) {
					sort($value);
					$values = implode(',',$value);
					$member->setAttribute($key,$values);
				} else {
					$member->setAttribute($key,$value);
				}
			}
		}
		$xml->save(InitMemberList::FileName());
	}
	private static function Delete($data)
	{
		$xml =& InitMemberList::Get()->Handle();
		$success = false;
		$nodeList = $xml->getElementsByTagName("member");
		foreach($nodeList as $node) {
			if(($data['id'] == $node->getAttribute('id')) && ($data['password'] == $node->getAttribute('password'))) {
				$node->parentNode->removeChild($node);
				$success = true;
			}
		}
		$xml->save(InitMemberList::FileName());
		return $success;
	}
	//Search関数　与えられた引数$dataの条件でXPathを実行する。
	//そしてヒットしたnodeのnodeListを返す
	//何も渡さずに呼び出す($data = null,$getID = null)とすべてのノードを返す。
	//$getIDにnull以外の値をセットするとIDのリストを返す
	private static function Search($data = null,$getID = null)
	{
		$xPath =& InitMemberList::Get()->XPath();
		$query = 'member';
		if($data) {
			list($key,$value) = each($data);
			$query .= '[@'.$key."='".$value."'";
			while(list($key,$value) = each($data)) {
				$query .= ' and @'.$key."='".$value."'";
			}
			$query .=']';
		}
		$nodeList = $xPath->query($query);
		if($getID) {
			$idList = array();
			foreach($nodeList as $node) {
				$idList[] = $node->getAttribute('id');
			}
			return array_unique($idList);
		} else {
			return $nodeList;
		}
	}
}

?>