<?php

//   error_reporting(E_ALL);

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


$finish = TRUE;

function plang($str) {
	return lang('plugin/geetest', $str);
}