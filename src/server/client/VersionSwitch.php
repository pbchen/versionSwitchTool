<?php
/**
*  
*/

include_once("Http.php");

class VersionSwitch {

	protected $url;
	protected $key;

	function __construct($url, $key){
		$this->url=$url;
		$this->key=$key;
	}

	function version_list(){
		$rt = $this->call_func($this->url."/version/list");
		return $rt;
	}

	function version_switch($siteid, $version, $reason){
		$siteid=urlencode($siteid);
		$version=urlencode($version);
		$reason=urlencode($reason);		
		$rt = $this->call_func($this->url."/version/switch?siteid=$siteid&version=$version&reason=$reason");
		return $rt;
	}

	function publish_upload($siteid, $version, $description, $md5, $type, $file){
		$siteid = urlencode($siteid);
		$version = urlencode($version);
		$description = urlencode($description);
		$md5 = urlencode($md5);
		$type = urlencode($type);
		$rt = $this->post_func($this->url."/publish/upload?siteid=$siteid&version=$version&description=$description&md5=$md5&type=$type"
								, array(), array(0=>$file));
		return $rt;
	}

	function version_delete($siteid, $version){
		$siteid=urlencode($siteid);
		$version=urlencode($version);
		$rt = $this->call_func($this->url."/version/delete?siteid=$siteid&version=$version");
		return $rt;
	}

	/*
	function get_func($url){
		
		if($http->request($url)){
			return  json_decode ($http->, true);
		}
		//if()
	}
	*/

	function post_func($url, $params, $files){
		$http=new Http($this->key);
		if($http->upload($url, $params, $files)){
			//var_export($http->get_data());
			return json_decode ($http->get_data(), true);
		} else {
			return array('result' => false, 'error' => 7.1);
		}
	}

	
	function call_func($url){
		//die($url);
		$useragent="VersionSwitch Client (http://www.adsage.com/)";
		$url_parsed = parse_url($url);
		//$scheme = $url_parsed["scheme"];
		$host = $url_parsed["host"];
		//$port = isset($url_parsed["port"]) ? $url_parsed["port"] : 80;
		$path = isset($url_parsed["path"]) ? $url_parsed["path"] : "/";
		$buffer='';

		if (function_exists('curl_exec')){
			$header=array('0' => "KEY: ".$this->key);
		   	$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; 
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		   	curl_setopt($ch, CURLOPT_USERAGENT,$useragent);
		   	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  
			$buffer = curl_exec($ch);
		   	curl_close($ch);
		} elseif ( $fp = @fsockopen($host, $port, $errno, $errstr, 30) )  {
			$header = "GET $path HTTP/1.0\r\nHost: $host\r\nKEY: ".$this->key."\r\n"
			             ."User-Agent: $useragent\r\n";
			$header.= "Connection: close\r\n\r\n";
		    fputs($fp, $header);
			fputs($fp, $query);
			if ($fp) while (!feof($fp)) $buffer.= fgets($fp, 8192);
			@fclose($fp);
		}
		return json_decode ($buffer, true);
	}
	
}
?>