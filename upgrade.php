<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) exit('Access Denied!');

loadcache('gt_cache');
loadcache('gt_mobile');
global $_G;
include_once DISCUZ_ROOT . "/source/plugin/geetest/lib/geetestlib.php";

if ($_G['cache']['gt_cache'] == "" || $_G['cache']['gt_cache'] == null || !isset($_G['cache']['gt_cache'])) {
    $config = @include DISCUZ_ROOT . 'source/plugin/geetest/lib/config.php';
    $pc_keyset = $config['keyset'];
}else {
    $pc_keyset = $_G['cache']['gt_cache'];
}

if ($_G['cache']['gt_mobile'] == "" || $_G['cache']['gt_mobile'] == null || !isset($_G['cache']['gt_mobile'])) {
    $config = @include DISCUZ_ROOT . 'source/plugin/geetest/lib/config.php';
    $mobile_keyset = $config['mobile'];
}else {
    $mobile_keyset = $_G['cache']['gt_mobile'];
}

update_config($pc_keyset);
update_config($mobile_keyset);

function update_config($config){
	$geetestlib = new geetestlib();
	$token = md5('discuz' . (string)time());
	$post_data = array('captchaid' => $config['captchaid'], 'privatekey' => $config['privatekey'], 'token' => $token);
	$result = $geetestlib->send_post("http://account.geetest.com/api/discuz/get", $post_data);
	$result = json_decode($result,true);
	if ($result['res'] == 0 || $result['res'] == 1) {
		if ($result['mobile'] == 1) {
			$gt_mobile['is_md5'] = $result['register'];
			savecache('gt_mobile',$gt_mobile); 
			$config['mobile'] = $gt_mobile;
			file_put_contents(DISCUZ_ROOT . '/source/plugin/geetest/lib/config.php', "<?php\n" . " return " . var_export($config, true) . ";?>");
		}elseif ($result['mobile'] == 0){
			$gt_cache['is_md5'] = $result['register'];
			savecache('gt_cache',$gt_cache); 
			$config['mobile'] = $gt_cache;
			file_put_contents(DISCUZ_ROOT . '/source/plugin/geetest/lib/config.php', "<?php\n" . " return " . var_export($config, true) . ";?>");
		}
	}
}

$finish = TRUE;

 ?>