<?php
$cfg = array();
//通信Key配置
$cfg['KEY'] = '123-456-789';
//.htaccess配置
$cfg['comments_start']='#-version-control-start';
$cfg['comments_end']='#-version-control-end';
$cfg['target_version'] = 'RewriteRule ^[^(\d+)].*$|^$ {target_version}/$0';
//上传和临时路径
$cfg['tmp_upload'] ='/work/temp/';
$cfg['tmp_unzip'] ='/work/temp/';
//Web网站配置
$cfg['paths'] =
	array(
	0 => array(
			'name' => 'test', 
			'description' => '测试站点',
			'cfg' => '.htaccess',
			'root' => '/work/web/services-dev.adsage.tk/deploy/test/', 
			'domain' => 'services-dev.adsage.tk/test/{ver}',
			'url' => 'http://services-dev.adsage.tk/test/',
			'publish' => false,
			'backup' => '/work/backup/web/services.adsage.tk_test/backup/',
			'upload' => '/work/backup/web/services.adsage.tk_test/upload/',
			
			),
	1 => array(
			'name' => 'switch-dev', 
			'description' => '独立域名演示',
			'cfg' => '.htaccess',
			'root' => '/work/web/switch-dev.adsage.tk/deploy/', 
			'domain' => '{ver}.switch-dev.adsage.tk',
			'url' => 'http://switch-dev.adsage.tk/',
			'backup' => '/work/backup/web/switch-dev.adsage.tk/backup/',
			'upload' => '/work/backup/web/switch-dev.adsage.tk/upload/',
			'publish' => true,
			),

	);
?>