<?php
$cfg = array();
//通信Key配置
$cfg['KEY'] = '123-456-789';
//.htaccess配置
$cfg['comments_start']='#default-version-start';
$cfg['comments_end']='#default-version-end';
$cfg['target_version'] = 'set $version {target_version};';
//上传和临时路径
$cfg['tmp_upload'] ='/work/temp/';
$cfg['tmp_unzip'] ='/work/temp/';
//Web网站配置
$cfg['paths'] =
	array(
	0 => array(
			'name' => 'test1', 
			'description' => '测试站点',
			'cfg' => '.htaccess',
			'root' => '/work/web/node.110.adsage.tk/deploy/', 
			'domain' => 'node.110.adsage.tk/{ver}',
			'url' => 'http://node.110.adsage.tk/',
			'publish' => false,
			'backup' => '/work/backup/web/node.110.adsage.tk_test/backup/',
			'upload' => '/work/backup/web/node.110.adsage.tk_test/upload/',
			
			),
	1 => array(
			'name' => 'apn-ss', 
			'description' => '版本提取演示',
			'cfg' => '.htaccess',
			'root' => '/work/web/apn-ss-dev.adsage.tk/deploy/', 
			'domain' => '{ver}.apn-ss-dev.adsage.tk',
			'url' => 'http://apn-ss-dev.adsage.tk/',
			'backup' => '/work/backup/web/apn-ss-dev.adsage.tk/backup/',
			'upload' => '/work/backup/web/apn-ss-dev.adsage.tk/upload/',
			'publish' => true,
			'versionback' => '/work/backup/web/apn-ss-dev.adsage.tk/versions/',
			),
	2 => array(
			'name' => 'apn-ss-test', 
			'description' => 'apn测试版',
			'cfg' => '.htaccess',
			'root' => '/work/web/apn-ss-test.adsage.tk/deploy/', 
			'domain' => '{ver}.apn-ss-test.adsage.tk',
			'url' => 'http://apn-ss-test.adsage.tk/',
			'backup' => '/work/backup/web/apn-ss-test.adsage.tk/backup/',
			'upload' => '/work/backup/web/apn-ss-test.adsage.tk/upload/',
			'publish' => true,
			'versionback' => '/work/backup/web/apn-ss-test.adsage.tk/versions/',
			),
	3 => array(
			'name' => 'soquairportal-dev', 
			'description' => 'soquairportal开发版',
			'cfg' => '.htaccess',
			'root' => '/work/web/soquairportal-dev.adsage.tk/deploy/', 
			'domain' => '{ver}.soquairportal-dev.adsage.tk',
			'url' => 'http://soquairportal-dev.adsage.tk/',
			'backup' => '/work/backup/web/soquairportal-dev.adsage.tk/backup/',
			'upload' => '/work/backup/web/soquairportal-dev.adsage.tk/upload/',
			'publish' => true,
			'versionback' => '/work/backup/web/soquairportal-dev.adsage.tk/versions/',
			),

	);
?>