<?php

include_once("../config/config.php");
include_once("../common.php"); 
include_once('VersionSwitch.php');
include_once("../phplib/log4php/Logger.php"); 
Logger::configure('../config/appender_dailyfile.properties');

$logger = Logger::getRootLogger();

function getGroups(){
	global $config;
	$gp = $config['groups'];
	$nodes = array();
	foreach ($gp as $key => $value) {
		array_push($nodes,  array('name' => $key, 'description' => $value['description']));
	}	
	return $nodes;
}


function sendmail($tomails, $ccmails, $title, $body){
	global $config;
    $url = $config['subscribes']['email_url'];
    $key = $config['subscribes']['email_key'];
    //$url = "http://services-dev.adsage.tk/uc/v2/Mail/Sendto";
    $post_string = array(
        'tomails' => trim($tomails, ','),
        'ccmails'=> trim($ccmails, ','),
        'title' => $title,
        'body' => $body,
        'signkey' => $key,
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $result = curl_exec($ch);
    //echo var_export($result);
    //$result = json_decode($result);
    curl_close($ch);

    return $result;
}


header("Content-Type: application/json;charset=utf-8");
$rt = array('result' => false, 'error' => -1 );
$logger->info('client/services '.$_SERVER['QUERY_STRING']);
$action=getParam('action', '');

switch($action){
	case 'groups':	
		$rt['value']=getGroups();
		$rt['result'] = true;
		$rt['error'] = 0;
		break;
	case 'site':		
		$gp=getParam('gp', '');
		$sitegp =  $config['groups'][$gp];
		if($sitegp!=null){
			$client = new VersionSwitch($sitegp['url'], $sitegp['key']);
			$rt = $client->version_list();
		}
		break;
	case 'switch':
		$gp = getParam('gp', '');
		$site = getParam('site', '');
		$ver = getParam('ver', '');
		$reason = getParam('reason', '');
		$sitegp =  $config['groups'][$gp];
		if($sitegp!=null){
			$client = new VersionSwitch($sitegp['url'], $sitegp['key']);
			$rt = $client->version_switch($site, $ver, $reason);
			if($rt && $config['subscribes']['enabled']){
				$url = getParam('purl', '');
				$verurl = 'http://'.str_replace('{ver}', $ver, getParam('pdomain', ''));
				$to = $config['subscribes']['switch_to'];
				if($to){
					$to .= ',';
				}
				$to .= $config['subscribes']['details'][$gp][$site]['switch_to'];
				//die($config['subscribes']['details'][$gp][$site]['switch_to']);
				if($to){
					sendmail($to, '',
						"[VersionSwitch] Node:[$gp] Site:[$site]-Switch to [$ver]",
						"Url: $url<br />Version Url: $verurl<br />". 
						"Reason: $reason<br />".
						"Result: ".$rt['result']."<br />Error: ".$rt['error']);
				}
			}
		}
		break;
	case 'delete':
		$gp = getParam('gp', '');
		$site = getParam('site', '');
		$ver = getParam('ver', '');
		$sitegp =  $config['groups'][$gp];
		if($sitegp!=null){
			$client = new VersionSwitch($sitegp['url'], $sitegp['key']);
			$rt = $client->version_delete($site, $ver);
			if($rt && $config['subscribes']['enabled']){
				$url = getParam('purl', '');
				$verurl = 'http://'.str_replace('{ver}', $ver, getParam('pdomain', ''));
				$to = $config['subscribes']['delete_to'];
				if($to){
					$to .= ',';
				}
				$to .= $config['subscribes']['details'][$gp][$site]['delete_to'];
				if($to){
					sendmail($to, '',
						"[VersionSwitch] Node:[$gp] Site:[$site]-Dwitch to [$ver]",
						"Url: $url<br />Version Url: $verurl<br />".
						"Result: ".$rt['result']."<br />Error: ".$rt['error']);
				}
			}
		}
		break;
	case 'publish':
		/*
array (
  'name' => 'v3.zip',
  'type' => 'application/octet-stream',
  'tmp_name' => '/tmp/phpRyVxtB',
  'error' => 0,
  'size' => 327,
)array (
  'action' => 'publish',
  'groupSites' => 'linux204',
  'siteItem' => 'test',
  'publish_func' => '上传',
  'publish_type' => '复制当前版本并覆盖',
  'md5' => '3FB11D47C60920ECD686A815013C30FD',
  'description' => '3FB11D47C60920ECD686A815013C30FD',
  'version_list' => '-1',
  'version_new_hide' => '3',
)
		*/
		$gp = getParam('groupSites', '');
		$site = getParam('siteItem', '');
		$func = getParam('publish_func', '');
		$type = getParam('publish_type', '');
		$md5 = getParam('md5', '');
		$description = getParam('description', '');
		$ver =  getParam('version_list', '');
		if($ver=='-1'){
			$ver =  getParam('version_new_hide', '');
		}
		$sitegp =  $config['groups'][$gp];
		if($sitegp!=null){
			$client = new VersionSwitch($sitegp['url'], $sitegp['key']);
			switch ($func) {
				case 'upload':
					if($_FILES['publish_file'] && $_FILES['publish_file']['error']==0 && $_FILES['publish_file']['size']>0){
						$file=$_FILES["publish_file"]["tmp_name"].'_'.$_FILES["publish_file"]["name"];
						move_uploaded_file($_FILES["publish_file"]["tmp_name"], $file);
						//die($file);
						$rt = $client->publish_upload($site, $ver, $description, $md5, $type, $file);
						if($rt && $config['subscribes']['enabled']){
							$url = getParam('purl', '');
							$verurl = 'http://'.str_replace('{ver}', $ver, getParam('pdomain', ''));
							$to = $config['subscribes']['publish_to'];
							if($to){
								$to .= ',';
							}
							$to .=  $config['subscribes']['details'][$gp][$site]['publish_to'];
							if($to){				
								sendmail($to, '',
									"[VersionSwitch] Node:[$gp] Site:[$site]-Publish to [$ver]",
									"Url: $url<br />Version Url: $verurl<br />".  
									"Publish: $func<br />Type:type<br />".
									"Description: $description<br />".
									"Result: ".$rt['result']."<br />Error: ".$rt['error']);
							}
						}

					} else {
						//7 publish upload 文件上传错误
						$rt['error'] = 7;
						if($_FILES['publish_file']&$_FILES['publish_file']['error']) {
							$rt['debug'] = $_FILES['publish_file']['error'].' '.MAX_FILE_SIZE;
						}
					}

					break;
			}

			
		}
		
		break;
	default:

	break;
}
//var_dump($rt);exit;
die(json_encode($rt));
?>