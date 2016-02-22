<?php

/**
 * 使用Get的方式返回：challenge和capthca_id 此方式以实现前后端完全分离的开发模式 专门实现failback
 * @author Tanxu
 */

// error_reporting(0);
require_once dirname(__FILE__) . '/lib/geetestlib.php';
$config = include dirname(__FILE__) . '/lib/config.php';
$keyset = $config['keyset'];
$GtSdk = new geetestLib();
session_start();
$_SESSION['gtsdk'] = $GtSdk;
$return = $GtSdk->register($config['keyset']['captchaid']);
if (strlen($return) == 32) {
    $_SESSION['gtserver'] = 1;
    if ($config['keyset']['is_md5'] == 1) {
	$result = array(
		'success' => 1,
	 	'gt' => $keyset['captchaid'],
	 	'challenge' => md5($GtSdk->challenge.$config['keyset']['privatekey']),
	 	);
    }else if($config['keyset']['is_md5'] == 0){
	$result = array('success' => 1, 'gt' => $keyset['captchaid'], 'challenge' => $GtSdk->challenge);
    }
    echo json_encode($result);
}else {
    $_SESSION['gtserver'] = 0;
    $rnd1 = md5(rand(0, 100));
    $rnd2 = md5(rand(0, 100));
    $challenge = $rnd1 . substr($rnd2, 0, 2);
    $result = array('success' => 0, 'gt' => $keyset['captchaid'], 'challenge' => $challenge);
    $_SESSION['challenge'] = $result['challenge'];
    echo json_encode($result);
}
?>