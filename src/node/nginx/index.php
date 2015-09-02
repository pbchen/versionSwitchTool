<?php
include('config.php');
include('data.php');
include('classes/Htaccess.php');
include('classes/FileUtil.php');
include('functions.php');

ini_set('display_errors',0);
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');

function getParam($name, $value){
	return isset($_REQUEST[$name])?$_REQUEST[$name]:$value;
}

function getList($hidepath = true){
	global $cfg, $data, $versions, $ext;
	$list = $cfg['paths'];
	$arrItems = array();
	foreach ($list as $idx => $val) {
		if($hidepath){
			//unset($val['root']);
			unset($val['cfg']);
		}
		$key=$val['name'];
		if($data[$key]!=null){
			$history = $data[$key];
			$last = count($history)-1;
			if($last>=0){
				$val['last_version']=$history[$last][0];
				$val['current_version']=$history[$last][1];
				$val['last_update']=$history[$last][2];
				$val['switch_description']=$history[$last][3];		
			}		
		}

		/****  start get the website all version *****/
		$oCurrentFolder = opendir( $val['root'] );
		$dir_arr = array();
		while (($file = readdir($oCurrentFolder)) !== false)
		{
			$dir_arr[] = $file;
		}
		closedir($oCurrentFolder);
		foreach($dir_arr as $v)
		{
			$path = $val['root'] . '/' . $v;
			if(is_dir($path) && is_numeric($v) && $v != '.' && $v != '..')
			{
				if( ! array_key_exists($v, $versions[$key]) )
				{
					$versions[$key][$v]['version'] = $v;
					$versions[$key][$v]['description'] = 'new upload version';
					$versions[$key][$v]['date'] = date('Y-m-d H:i:s',filemtime($path));
				}
			}
		}
		/***************   end   ********************/
		$val['versions'] = $versions[$key];
		$val['ext'] = $ext[$key];
		$arrItems[$key]=$val;	
	}
	return $arrItems;
}

function switchVersion($id, $version, $reason) {
    global $cfg, $data, $versions, $ext;
    $rt = array('result' => false, 'error' => 3);
    //验证参数
    if ($id == '' || $version == '' || $reason == '') {
        return $rt;
    }

    $list = getList(false);
    $site = $list[$id];
    if ($site != null) {
        $path = $site['root'] . $version;

        if ($site['current_version'] == $version) {
            //当前版本号等于目标版本号
            $rt['error'] = 4;
        } else if (!file_exists($path)) {
            //目标版本不存在
            $rt['error'] = 5;
            $rt['debug'] = $path;
        } else {

            $ht = new Htaccess($site['root'] . $site['cfg'], $cfg['comments_start'], $cfg['comments_end']);
            if ($ht && $ht->errors() == 0) {
                $ht->add_rule(get_all_rules($cfg, $version));
                $ht->save();
                if ($ht->errors() == 0) {
                    $ht->exec();
                    if ($ht->errors() == 0) {
                        if ($data[$id] == null) {
                            $data[$id] = array();
                        }
                        array_push($data[$id], array($site['current_version'], $version, date("Y-m-d H:i:s", time()), $reason));
                        $rt['error'] = 0;
                        $rt['result'] = true;
                    } else {
                        $rt['error'] = '-2';
                    }
                } else {
                    $rt['error'] = 6;
                }
            } else {
                $rt['error'] = 6;
            }
        }
    } else {
        //3 switch 参数错误
        $rt['error'] = 3;
    }
    save_records($data, $ext, $versions);
    return $rt;
}

function deleteVersion($id, $version){
	global $cfg, $data, $versions, $ext;
	$rt = array('result' => false, 'error' => '-6' );
	//验证参数
	if($id=='' || $version==''){
		return $rt;
	}
	$list = getList(false);
	$site = $list[$id];
	if($site!=null){		
		$path = $site['root'].$version;	

		if($site['current_version']==$version){
			//当前版本号处于运行状态
			$rt['error'] = '-3';
		} else if(!file_exists($path)){			
			//目标版本不存在
			if(isset($versions[$id])) unset($versions[$id]);
			$rt['error'] = '-4';
			$rt['debug'] = $path;
			
		} else{
			if(!isset($site['versionback']) || !$site['versionback']){
				$site['versionback'] = '/work/backup/web/'.$_SERVER['HTTP_HOST'].'/versions/';
			}
			$versionbackPath = makeDir($site['versionback']);
			if($versionbackPath){
				//backup($path, $versionbackPath.getRandomName($siteid.'_'.$version.'.zip'));
				if(delDirAndFile($path)){
					if(isset($versions[$id])) unset($versions[$id]);
					$rt = array('result' => true, 'error' => 0 );
				} else{
					$rt = array('result' => false, 'error' => '-6' );
				}
			} else{
				//-7 备份目录创建失败
				$rt = array('result' => false, 'error' => '-7' );
			}
		}

	} else {
		//-5 delete 参数错误
		$rt['error'] = '-5';
	}
	save_records($data, $ext, $versions);
	return $rt;
}

function get_all_rules($cfg, $version){
	$rules=get_ext_rule();
	$rules.="\n".get_switch_rule($cfg, $version);
	return $rules;
}

function get_ext_rule(){
	return '';
}

function get_switch_rule($cfg, $version){
	return str_replace('{target_version}', $version, $cfg['target_version']);
}

function save_records($data, $ext, $versions){
	$output="<?php \n".'$data='.var_export($data, true)
	.";\n".'$versions='.var_export($versions, true)
	.";\n".'$ext='.var_export($ext, true)
	.";\n?>";
	file_put_contents('data.php', $output);
}

function publish_upload($siteid, $version, $description, $md5, $type){
	global $cfg;	
	$rt = array('result' => false, 'error' => 7 ,'msg'=>'debug');
	if($_FILES){
		$key = '0';
		foreach($_FILES as $k=>$v){
			$key = $k;
			break;
		}
		$f=$_FILES[$key];
		if((($f['type'] == 'application/zip')
			|| ($f['type'] == 'application/x-gzip')
			|| ($f['type'] == 'application/x-tar'))) {
			if ($f['error'] ==0) {
				$filename = $cfg['tmp_upload'].getRandomName($siteid.'_'.$version.'_'.$md5.'_'.$f['name']);
				move_uploaded_file($f['tmp_name'], $filename);
				$rt = publish($siteid, $version, $description, $filename, $md5, $type);
				//$rt['msg'] = 'fail';
			}
		 		
		}
	}
	//$rt['msg'] = $filename;
 	return $rt;
		
}

function publish($siteid, $version, $description, $filename, $md5, $type){
	global $cfg, $data, $versions, $ext;
	$rt = array('result' => false, 'error' => -1 );
	$debug = '1';
	$list = getList(false);
	$site = $list[$siteid];
	//$debug= json_encode($site);
	//$debug = $description;
	//file_put_contents("debug.log", strtolower(md5_file($filename)))
	//file_put_contents("debug.log", strtolower(md5_file($filename)))
	if($site!=null && file_exists($filename) && 
		strtolower(md5_file($filename)) == strtolower(trim($md5))){
		if($site['current_version']==$version){
			//10 publish 发布版本和线上版本一致
			$rt['error'] = 10;
			return $rt;
		}
		//检查目标路径
		$p = $site['root'].$version;
		$tmp = $cfg['tmp_unzip'] . getRandomName($siteid.'_'.$version.'_tmp/');
		if(file_exists($p)){
			//如果存在则备份配置
			//删除已经存在的文件目录
			if($site['backup']){
				backup($p, $site['backup'].getRandomName($siteid.'_'.$version.'.zip'));
			}
			$r = delDirAndFile($p);
			if($r==0) {
				$debug=$p;
				//9 publish 目录创建失败
				$rt['error'] = 9;
			}
		}
		if($site['upload']){
			//备份上传文件
			copy($filename, $site['upload']. str_replace($cfg['tmp_upload'], '', $filename));			
		}
		if($rt['error'] ==-1){
			if (!mkdir($p) || !mkdir($tmp)) {
				//9 publish 目录创建失败
				$debug=$p . ' ' .$tmp;
			    $rt['error'] = 9;
			} else {
				if($type&&strtolower($type)=='copyreplace'){ 
					//拷贝当前版本		
					FileUtil::copyDir($site['root'].$site['current_version'], $p, true);

				}
				
				//解压缩文件到临时目录
				$z = new ZipArchive();
				//die(file_exists($filename));
				$z->open($filename);
				$z->extractTo($tmp);
				//将临时目录下的deploy目录拷贝到目标路径
				if(file_exists($tmp.'deploy')){
					//将部署程序从deploy目录下拷贝到目标路径
					FileUtil::copyDir($tmp.'deploy',$p, true);
					if($versions[$siteid]==null){
						$versions[$siteid]=array();
					}
					$intver = intval($version);
					$versions[$siteid][$intver]=array(
						'version' => $intver,
						'description' => $description,
      					'date' => date("Y-m-d H:i:s",time()),
					);
					save_records($data, $ext, $versions);
					$rt['error']=0;
					$rt['result']=true;
				}
				else {
					$rt['error']=7;
					$debug='上传文件无deploy目录';	
				}
				//删除临时目录
				/*
				FileUtil::unlinkFile($filename);
				FileUtil::unlinkDir($tmp);
				*/
			}
		}		

	} else {
		//8 publish upload md5 校验失败
		$rt['error'] = 8;
	}
	$rt['msg'] = $debug;
	return $rt;
}



header("Content-Type: application/json;charset=utf-8");
$rt = array('result' => false, 'error' => -1 );
//-1 KEY设定错误
//0 无错误
//1 没有service参数
//2 Service内部错误
//3 switch 参数错误
//4 switch 当前版本号等于目标版本号
//5 switch 目标版本不存在
//6 switch htaccess 文件读写错误
//7 publish upload 文件上传错误
//8 publish upload md5 校验失败
//9 publish 目录创建失败
//10 publish 发布版本和线上版本一致，无法覆盖

//检查KEY的设定是否正确  
if(getenv('HTTP_KEY')==$cfg['KEY']){
//if(true){
	$service =   getParam('service','');
	$func =   getParam('func','');
	
	if($service!=''){
		switch ($service) {
			case 'version':
				switch($func){
					case 'list':
						$rt['result'] = true;
						$rt['value'] = getList();
						break;
					case 'switch':
						$siteid  =   getParam('siteid','');
						$version =   getParam('version','');				
						$reason  =   getParam('reason','');
						$rt = switchVersion($siteid, $version, $reason);
						break;
					case 'delete':
						$siteid  =   getParam('siteid','');
						$version =   getParam('version','');				
						$rt = deleteVersion($siteid, $version);
						break;
				}
				break;
			case 'publish':
				switch($func){
					case 'upload':
						$siteid		  =   getParam('siteid','');
						$version   	  =   getParam('version','');
						$description  =   getParam('description','');
						$md5   		  =   getParam('md5','');
						$type   	  =   getParam('type','');						
						
						$rt = publish_upload($siteid, $version, $description, $md5, $type);
					break;
				}
					
				break;
			default:
				$rt['error']=1;
				break;
		}
		//检查返回值
		/*
		if($rt['value']!=null){
			$rt['result']=true;
			$rt['error']=0;
		} else {
			$rt['error']=2;
		}
		*/
		
	} else {
		//没有Service参数或者Service对应的方法不存在
		$rt['error']=1;
	}


} else {
	//默认就是KEY设置错误的返回消息
}

die(json_encode($rt));



?>