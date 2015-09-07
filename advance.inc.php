<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
// include_once DISCUZ_ROOT."/source/plugin/geetest/lib/geetestlib.php";
loadcache("gt_cache");


$privatekey = md5($_G['cache']['gt_cache']['privatekey']);
$url = "http://account.geetest.com/api/discuz/login?captchaid=".$_G['cache']['gt_cache']['captchaid']."&privatekey=".$_G['cache']['gt_cache']['privatekey']."&token=".md5('discuz'.(string)time()).'&random='.rand(1000000000,9999999999);
$html = <<<HTML
	<iframe src="{$url}" style="height:820px;width:1200px;border:none;background:white"></iframe>


HTML;
echo $html;







 ?>