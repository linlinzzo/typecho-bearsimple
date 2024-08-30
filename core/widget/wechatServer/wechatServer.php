<?php
ob_clean();
error_reporting(0);
require __DIR__ . '/vendor/autoload.php';
include 'Config.php';
require('bearWechatHandler.php');
use EasyWeChat\OfficialAccount\Application;
require_once 'WechatUtils.php';
$options = Helper::options();

function typeImageContent($url, $b)
{
	$c = WechatUtils::uploadPic($b, uniqid(), $url, 'web', '.jpg');
	$url = $c.',';
	return $url;
}
function typeTextContent($a, $c = true)
{
	if ($c) {
		$a = $a .'
';
	}
	return $a;
}

function parseCircleContent($data, $options)
{
	$arr = json_decode($data, true);
	$arr = $arr['results'];
	$data = array();
	$data['text'] = '';
	$images = '';
	foreach ($arr as $con2) {
		if ($con2['type'] == 'image') {
			$images .= typeImageContent($con2['content'], $options->rootUrl);
			$filtered_arr = array_filter(explode(',', $images), function($value) {
    return $value !== '' && $value !== null;
});
			$data['images'] = json_encode($filtered_arr);
			
		} elseif ($con2['type'] == 'text') {
			$data['text'] .= typeTextContent($con2['content'], true);
		} 
	}
	return $data;
}

// 获取MD5加密后的内容
function getEncryptToken(){
    $db = Typecho_Db::get();
    return md5(md5("bearsimple!@#$%^&*()-=+@#$%$" . Helper::options()->openId . "bearsimple!@#$%^&*()-=+@#$%$@#$%^&*"));

}
switch($_POST['action']){
    case 'sendCircle':
        $options = Helper::options();
      if (!empty($_POST['content']) && !empty($_POST['postEncryptToken']) && !empty($_POST['cid']) && !empty($_POST['agent'])) {
			$cid = $_POST['cid'];
			$thisText = parseCircleContent($_POST['content'], $options)['text'];
			$postEncryptToken = $_POST['postEncryptToken'];
			$agent = $_POST['agent'];
			$msg_type = $_POST['msg_type'];
            $encryptToken = getEncryptToken();

            if (md5($postEncryptToken) == $encryptToken) {
					$db = Typecho_Db::get();
					$getAdminSql = $db->select()->from('table.users')->limit(1);
					$user = $db->fetchRow($getAdminSql);
					$time = time();
					$queryTables = $db->fetchRow($db->select()->from('table.comments')->where('cid = ?', $cid)->where('created = ?', $time));
					$msgid = \Typecho\Cookie::get("msgid");
					if(isset($msgid) && $msgid == $_POST["msgid"]){
					    	echo '1';
					    	break;
					}
					else{
					    \Typecho\Cookie::set("msgid",$_POST["msgid"]);
					}
					$insert = $db->insert('table.comments')->rows(array('cid' => $cid, 'created' => $time, 'author' => $user['screenName'], 'authorId' => $user['uid'], 'ownerId' => $user['uid'], 'text' => $thisText, 'url' => $user['url'], 'mail' => $user['mail'], 'agent' => $agent, 'ip' => '1.1.1.1'));
					$insertId = $db->query($insert);
					$row = $db->fetchRow($db->select('commentsNum')->from('table.contents')->where('cid = ?', $cid));
					$db->query($db->update('table.contents')->rows(array('commentsNum' => (int) $row['commentsNum'] + 1))->where('cid = ?', $cid));
					$queryTable = $db->fetchRow($db->select()->from('table.comments')->where('cid = ?', $cid)->where('created = ?', $time));
					$circle_data = array(
                'coid' => $queryTable['coid'],
                'location' => '',
                'private' => false,
                'resources' => parseCircleContent($_POST['content'], $options)['images'],
            );
    $db->query($db->insert('table.bscore_friendcircle_data')->rows($circle_data));
					echo '1';
				
			} else {
				echo '-2';
			}
		} else {
			echo '-3';
		}  
        break;
    case 'saysTalk':
		if (!empty($_POST['content']) && !empty($_POST['postEncryptToken']) && !empty($_POST['cid']) && !empty($_POST['agent'])) {
			$cid = $_POST['cid'];
			$thisText = $_POST['content'];
			$postEncryptToken = $_POST['postEncryptToken'];
			$agent = $_POST['agent'];
			$msg_type = $_POST['msg_type'];
            $encryptToken = getEncryptToken();

            if (md5($postEncryptToken) == $encryptToken) {
					$db = Typecho_Db::get();
					$getAdminSql = $db->select()->from('table.users')->limit(1);
					$user = $db->fetchRow($getAdminSql);
					$insert = $db->insert('table.comments')->rows(array('cid' => $cid, 'created' => time(), 'author' => $user['screenName'], 'authorId' => $user['uid'], 'ownerId' => $user['uid'], 'text' => $thisText, 'url' => $user['url'], 'mail' => $user['mail'], 'agent' => $agent, 'ip' => '1.1.1.1'));
					$insertId = $db->query($insert);
					$row = $db->fetchRow($db->select('commentsNum')->from('table.contents')->where('cid = ?', $cid));
					$db->query($db->update('table.contents')->rows(array('commentsNum' => (int) $row['commentsNum'] + 1))->where('cid = ?', $cid));
					echo '1';
				
			} else {
				echo '-2';
			}
		} else {
			echo '-3';
		}
	
        break;
        default:
$app = new Application($config);
$app->server->push(BearWechatHandler::class);
$response = $app->server->serve();
$response->send();
}