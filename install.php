<?php 

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache("gt_cache");
loadcache("gt_mobile");
global $_G;
if (empty($_G['cache']['gt_cache']) || isset($_G['cache']['gt_cache'])) {

      $mobile_result = dfsockopen('http://my.geetest.com/api/discuz/add');
      $mobile_result = json_decode($mobile_result,true);
      savecache('gt_cache',$mobile_result);
}
if (empty($_G['cache']['gt_mobile']) || isset($_G['cache']['gt_mobile'])) {

      $web_result = dfsockopen('http://my.geetest.com/api/discuz/mobile');
      $web_result = json_decode($web_result,true);
      savecache('gt_mobile',$web_result);
}


$finish = TRUE;

?>