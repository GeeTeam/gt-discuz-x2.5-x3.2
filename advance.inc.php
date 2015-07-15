<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
// include_once DISCUZ_ROOT."/source/plugin/geetest/lib/geetestlib.php";
loadcache("gt_cache");


$privatekey = md5($_G['cache']['gt_cache']['privatekey']);
$html = <<<HTML
	<iframe src="http://my.geetest.com/api/discuz/login/captchaid={$_G['cache']['gt_cache']['captchaid']}privatekey={$privatekey}/" style="height:820px;width:1200px;border:none;background:white"></iframe>


HTML;
echo $html;







 ?>