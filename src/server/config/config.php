<?php
$config=array();
/*用户名密码*/
$config['username'] = 'test';
$config['password'] = 'test';
/*服务器探针*/
$config['probe'] = 'plugins/tz.php';
$config['version'] = '0.2';

/*管理节点1 配置*/
$linux=array();
$linux['url']='http://services-dev.adsage.tk/version/plugins/apache/';
$linux['key'] = '123-456-789'; 				//服务器与客户端的通信密钥
$linux['description'] = 'Linux Apache 204'; //描述

/*管理节点2 配置*/
$win=array();
$win['url']='http://172.16.18.5:3000/';
$win['key'] = '123-456-789';				//服务器与客户端的通信密钥
$win['description'] = 'Windows IIS 测试';   //描述

/*管理节点3 配置*/
$nginx=array();
$nginx['url']='http://node.110.adsage.tk/plugins/nginx';
$nginx['key']= '123-456-789';
$nginx['description'] = 'Linux Nginx 110';

/*管理节点4 配置*/
$smg=array();
$smg['url']='http://smg.adsage.tk/plugins/nginx';
$smg['key']= '123-456-789';
$smg['description'] = 'Linux Nginx 4.110';

$config['groups']= array('linux204' => $linux,'windows' => $win, 'linux110' => $nginx, 'linux-4-110'=> $smg );

/*管理节点5 配置*/
$linux74=array();
$linux74['url']='http://vda-ps-node.adsage.tk/nginx';
$linux74['key']= '123-456-789';
$linux74['description'] = 'Linux Nginx 18.74';

$config['groups']= array('linux204' => $linux,'windows' => $win, 'linux110' => $nginx, 'linux-4-110'=> $smg, 'linux74'=>$linux74);

/*Email 订阅*/
//是否启用订阅
$config['subscribes']['enabled'] = false;
//发布通知,多个逗号分隔
$config['subscribes']['publish_to']='yinjun@adsage.com';
//切换通知,多个逗号分隔
$config['subscribes']['switch_to']='yinjun@adsage.com';
//订阅具体项目发布通知,多个逗号分隔
$config['subscribes']['details']['linux204']['test']['publish_to']='83373821@qq.com';
//订阅具体项目切换通知,多个逗号分隔
$config['subscribes']['details']['linux204']['test']['switch_to']='83373821@qq.com';

//Email发送Service配置
$config['subscribes']['email_url'] = 'http://services.adsage.com/uc/v2/Mail/Sendto';
$config['subscribes']['email_key'] = 'a59abbe56e';
?>