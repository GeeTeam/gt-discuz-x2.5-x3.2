<?php
/*
 * Copyright (c) 2011 by geetest.com
 * Author: JayzWoo
 * Created: 2011-5-5
 * Function: geetest API php code
 * Version: v2.4
 * Date: 2013-3-19
 * PHP library for geetest - 脫隆脧贸脗毛 - 脩茅脰陇脗毛鹿茫赂忙脭脝路镁脦帽脝陆脤篓.
 *    - Documentation and latest version
 *          http://www.geetest.com/
 *    - Get a geetest API Keys
 *          http://www.geetest.com/server/signup.php
 */

define('GT_API_SERVER', 'http://api.geetest.com');
define('GT_SDK_VERSION', 'discuz_1.0');

class geetestlib{
	private $config = array();
	
	public function __construct(){
		$this->challenge = "";
		$this->config = array(
                	"captchaid" => "",
                	"privatekey" => ""
            		);
	}
	public static function get_widget_mobile($captcha){
	      return '<script type="text/javascript" src="http://api.geetest.com/get.php?gt='.$captcha.'&product=embed&width=300" async></script>';
	}

	function register($captchaid) {
		$this->challenge = $this->_send_request("/register.php", array("gt"=>$captchaid));
		if (strlen($this->challenge) != 32) {
			return 0;
		}
		return 1;
	}

	function get_widget($captchaid,$product, $popupbtnid="") {
		$params = array(
			"gt" => $captchaid,
			"product" => $product,
			"sdk" => GT_SDK_VERSION,
			"rand" => rand(),
		);
		if ($product == "popup") {
			$params["popupbtnid"] = $popupbtnid;
		}
		return '<script type="text/javascript" src="'.GT_API_SERVER.'/get.php?'.http_build_query($params).'"></script>';
	}
	
	
	public function set_keyset($keyset){
		$this->config = array_merge($this->config, $keyset);
	}
	
	function validate($challenge, $validate, $seccode) {	
		if ( ! $this->_check_validate($challenge, $validate)) {
			return FALSE;
		}
		$query = 'seccode='.$seccode;
		$codevalidate = $this->_http_post('api.geetest.com', '/validate.php', $query);
		if (strlen($codevalidate)>0 && $codevalidate==md5($seccode)) {
			return 1;
		} else if ($codevalidate == "false"){
			return 0;
		} else { 
			return $codevalidate;
		}
		return -1;
	}

	function _check_validate($challenge, $validate) {
		if (strlen($validate) != 32) {
			return FALSE;
		}
		if (md5($this->config['privatekey'].'geetest'.$challenge) != $validate) {
			return FALSE;
		} 
		return TRUE;
	}

	private function _http_post($host,$path,$data,$port = 80){
		$http_request = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$http_request .= "Content-Length: " . strlen($data) . "\r\n";
		$http_request .= "\r\n";
		$http_request .= $data;
		$response = '';
		if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false) {
			die ('Could not open socket! ' . $errstr);
		}		
		fwrite($fs, $http_request);
		while (!feof($fs))
			$response .= fgets($fs, 1160);
		fclose($fs);		
		$response = explode("\r\n\r\n", $response, 2);
		return $response[1];
	}

	function _send_request($path, $data, $method="GET") {
		$data['sdk'] = GT_SDK_VERSION;

		if ($method=="GET") {
			$opts = array(
			    'http'=>array(
				    'method'=>"GET",
				    'timeout'=>2,
			    )
		    );
		    $context = stream_context_create($opts);
			$response = file_get_contents(GT_API_SERVER.$path."?".http_build_query($data), false, $context);

		} 
		return $response;
	}


	public function send_post($url, $post_data) {
		$postdata = http_build_query($post_data);
		$options = array(
			'http' => array(
				'method' => 'POST',
				'header' => 'Content-type:application/x-www-form-urlencoded',
				'content' => $postdata,
				'timeout' => 15 * 60 // expire time
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $result;
	}
}

