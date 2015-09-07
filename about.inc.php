<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
// include_once DISCUZ_ROOT."/source/plugin/geetest/lib/geetestlib.php";
loadcache("gt_cache");


$privatekey = md5($_G['cache']['gt_cache']['privatekey']);
$url = "http://www.geetest.com/install/sections/idx-plugins.html#discuz";
$html = <<<HTML
    <iframe src="{$url}" style="height:820px;width:1200px;border:none;background:white"></iframe>

HTML;
echo $html;







 ?>