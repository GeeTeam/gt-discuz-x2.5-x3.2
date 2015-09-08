<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$config = include DISCUZ_ROOT.'data/plugindata/geetest/config.php';

$url = "http://account.geetest.com/api/discuz/login?captchaid=".$config['webset']['captchaid']."&privatekey=".$config['webset']['privatekey']."&token=".md5('discuz'.(string)time()).'&random='.rand(1000000000,9999999999);
$html = <<<HTML
	<iframe src="{$url}" style="height:820px;width:1200px;border:none;background:white"></iframe>
HTML;
echo $html;

 ?>