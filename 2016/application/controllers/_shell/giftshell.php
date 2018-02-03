<?php
if(! defined('BASEPATH')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 奖品文件缓存初始化
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-7-8
 ****************************************************************/
class GiftShell extends CI_Controller
{
	/* 配置容器 */
	private $_config = array();

	function __construct()
    {
		parent::__construct();
		/* 载入礼物信息 */
		$this->_config = $this->config->item('gift');
		/* 加载文件缓存类 */
		$this->load->library(array('CNFOL_FileCache'));
		//脚本停止
		exit;
		
    }
	/**
	 * 每天00:00奖品列表及用户列表备份,详见crontab
	 * @return void
	 */
	public function initGift()
	{
		$file = 'initgift';
		$date = date('Hi');
		$date = '0000';
		if($date>='0000' && $date<='0010')
		{
			/* pc版奖品列表备份 */
			if(file_exists(CACHE_PATH.'cache_pc_giftlist'))
				$isSucc = rename(CACHE_PATH.'cache_pc_giftlist',CACHE_PATH.'cache_pc_giftlist_'.date('Ymd'));
				
			$this->log('cache_pc_giftlist:'.$isSucc,$file);
			$this->cnfol_filecache->set('pc_giftlist',$this->_config['pc']);

			/* 3g版奖品列表备份 ##暂时撤销 */
		/*	if(file_exists(CACHE_PATH.'cache_3g_giftlist'))
				$isSucc = rename(CACHE_PATH.'cache_3g_giftlist',CACHE_PATH.'cache_3g_giftlist_'.date('Ymd'));
			
			$this->log('cache_3g_giftlist:'.$isSucc,$file);
			$this->cnfol_filecache->set('3g_giftlist',$this->_config['3g']);*/

			/* 用户列表备份 */
			if(file_exists(CACHE_PATH.'cache_userlist'))
				$isSucc = rename(CACHE_PATH.'cache_userlist',CACHE_PATH.'cache_userlist_'.date('Ymd'));
			
			$this->log('cache_userlist:'.$isSucc,$file);
			//$this->_cache->set('userlist',$this->_config['pc']);

			unset($this->_config);
		}
		else
		{
			$this->log('==============',$file);
		}
	}

	/**
	 * 每天2小时插入一些虚拟用户,详见crontab
	 * @return void
	 */
	public function setVirtualUser()
	{
		$file = 'setVirtualUser';
		$date = date('Hi');
		(string)$phonedate = date("YmdHis");
		echo $phonedate;
		$phone = '';
		$phoneArr = array(130,131,132,133,134,135,136,137,138,139,150,151,152,153,155,156,157,158,159,186,183,187,188,189);
		$windList = $this->cnfol_filecache->get('windlist');
		//苹果6s
		if($_GET['flag']=='cxm')
		{
			//获奖的虚拟手机
			$vm1 = '157****7152(福建)';
			$arr = array($vm1);
			$username = $arr[0];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'iphone6s','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|iphone6s|mark:1|time:'.date('Ymd'),$file);
		}
		if($_GET['flag']=='wjg')
		{
			//获奖的虚拟手机
			$vm1 = '133****0457(福建)';
			$arr = array($vm1);
			$username = $arr[0];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'iphone6s','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|iphone6s|mark:1|time:'.date('Ymd'),$file);
		}
		if($_GET['flag']=='zmw')
		{
			//获奖的虚拟手机
			$vm1 = '180****9892(福建)';
			$arr = array($vm1);
			$username = $arr[0];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'iphone6s','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|iphone6s|mark:1|time:'.date('Ymd'),$file);
		}
		if($date >= '0900' && $date <= '0900')
		{
			//获奖的虚拟手机
			$area = $this->returarea();
			$vm1 = $phoneArr[rand(0,23)].'****'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm2 = 'CE-'.rand(1,9).'****'.rand(11,99).'(未知)';
			$area = $this->returarea();
			$vm3 = 'qq:'.rand(111,999).'****'.rand(111,999).'(未知)';
			$area = $this->returarea();
			$vm4 = 'zj:******'.rand(1111,9999).'(未知)';
			$area = $this->returarea();
			$vm5 = 'sin:********'.rand(1111,9999).'(未知)';
			$area = $this->returarea();
			$vm6 = 'wx:********'.rand(1111,9999).'(未知)';
			$arr = array($vm1,$vm2,$vm3,$vm4,$vm5,$vm6);
			$key = rand(0,5);
			$username = $arr[$key];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'25元红包','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|25元红包|mark:1|time:'.date('Ymd'),$file);

		}
		/*话费*/
		if($date >= '0900' && $date <= '2300')
		{
			//获奖的虚拟手机
			$area = $this->returarea();
			$vm1 = $phoneArr[rand(0,23)].'****'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm2 = 'CE-'.rand(1,9).'****'.rand(11,99).'('.$area.')';
			$area = $this->returarea();
			$vm3 = 'qq:'.rand(111,999).'****'.rand(111,999).'('.$area.')';
			$area = $this->returarea();
			$vm4 = 'zj:******'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm5 = 'sin:********'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm6 = 'wx:********'.rand(1111,9999).'('.$area.')';
			$arr = array($vm1,$vm2,$vm3,$vm4,$vm5,$vm6);
			$key = rand(0,5);
			$username = $arr[$key];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'10元话费','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|10元话费|mark:1|time:'.date('Ymd'),$file);

		}
		/*话费*/
		if($date >= '0900' && $date <= '2300')
		{
			//获奖的虚拟手机
			$area = $this->returarea();
			$vm1 = $phoneArr[rand(0,23)].'****'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm2 = 'CE-'.rand(1,9).'****'.rand(11,99).'('.$area.')';
			$area = $this->returarea();
			$vm3 = 'qq:'.rand(111,999).'****'.rand(111,999).'('.$area.')';
			$area = $this->returarea();
			$vm4 = 'zj:******'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm5 = 'sin:********'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm6 = 'wx:********'.rand(1111,9999).'('.$area.')';
			$arr = array($vm1,$vm2,$vm3,$vm4,$vm5,$vm6);
			$key = rand(0,5);
			$username = $arr[$key];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'10元话费','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|10元话费|mark:1|time:'.date('Ymd'),$file);

		}
		/*话费*/
		if($date >= '0900' && $date <= '2300')
		{
			//获奖的虚拟手机
			$area = $this->returarea();
			$vm1 = $phoneArr[rand(0,23)].'****'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm2 = 'CE-'.rand(1,9).'****'.rand(11,99).'('.$area.')';
			$area = $this->returarea();
			$vm3 = 'qq:'.rand(111,999).'****'.rand(111,999).'('.$area.')';
			$area = $this->returarea();
			$vm4 = 'zj:******'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm5 = 'sin:********'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm6 = 'wx:********'.rand(1111,9999).'('.$area.')';
			$arr = array($vm1,$vm2,$vm3,$vm4,$vm5,$vm6);
			$key = rand(0,5);
			$username = $arr[$key];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'10元话费','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|10元话费|mark:1|time:'.date('Ymd'),$file);

		}
		if($date >= '1000' && $date <= '2000')
		{
			//获奖的虚拟手机
			$area = $this->returarea();
			$vm1 = $phoneArr[rand(0,23)].'****'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm2 = 'CE-'.rand(1,9).'****'.rand(11,99).'('.$area.')';
			$area = $this->returarea();
			$vm3 = 'qq:'.rand(111,999).'****'.rand(111,999).'('.$area.')';
			$area = $this->returarea();
			$vm4 = 'zj:******'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm5 = 'sin:********'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm6 = 'wx:********'.rand(1111,9999).'('.$area.')';
			$arr = array($vm1,$vm2,$vm3,$vm4,$vm5,$vm6);
			$key = rand(0,5);
			$username = $arr[$key];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'精美相框','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|精美相框|mark:1|time:'.date('Ymd'),$file);

		}
		if($date >= '0800' && $date <= '2300')
		{
			//获奖的虚拟手机
			$area = $this->returarea();
			$vm1 = $phoneArr[rand(0,23)].'****'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm2 = 'CE-'.rand(1,9).'****'.rand(11,99).'(未知)';
			$area = $this->returarea();
			$vm3 = 'qq:'.rand(111,999).'****'.rand(111,999).'(未知)';
			$area = $this->returarea();
			$vm4 = 'zj:******'.rand(1111,9999).'(未知)';
			$area = $this->returarea();
			$vm5 = 'sin:********'.rand(1111,9999).'(未知)';
			$area = $this->returarea();
			$vm6 = 'wx:********'.rand(1111,9999).'(未知)';
			$arr = array($vm1,$vm2,$vm3,$vm4,$vm5,$vm6);
			$key = rand(0,5);
			$username = $arr[$key];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'50元红包','mark'=>1,'time'=>date('Ymd')));
			
			$this->log('username:'.$username.'|50元红包|mark:1|time:'.date('Ymd'),$file);

			//获奖的虚拟手机
			$area = $this->returarea();
			$vm1 = $phoneArr[rand(0,23)].'****'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm2 = 'CE-'.rand(1,9).'****'.rand(11,99).'(未知)';
			$area = $this->returarea();
			$vm3 = 'qq:'.rand(111,999).'****'.rand(111,999).'(未知)';
			$area = $this->returarea();
			$vm4 = 'zj:******'.rand(1111,9999).'(未知)';
			$area = $this->returarea();
			$vm5 = 'sin:********'.rand(1111,9999).'(未知)';
			$area = $this->returarea();
			$vm6 = 'wx:********'.rand(1111,9999).'(未知)';
			$arr = array($vm1,$vm2,$vm3,$vm4,$vm5,$vm6);
			$key = rand(0,5);
			$username = $arr[$key];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'50元红包','mark'=>2,'time'=>date('Ymd')));

			$this->log('username:'.$username.'|100元红包|mark:2|time:'.date('Ymd'),$file);
		}	
		if($date >= '2000' && $date <= '2100')
		{
			//获奖的虚拟手机
			$area = $this->returarea();
			$vm1 = $phoneArr[rand(0,23)].'****'.rand(1111,9999).'('.$area.')';
			$area = $this->returarea();
			$vm2 = 'CE-'.rand(1,9).'****'.rand(11,99).'(未知)';
			$area = $this->returarea();
			$vm3 = 'qq:'.rand(111,999).'****'.rand(111,999).'(未知)';
			$area = $this->returarea();
			$vm4 = 'zj:******'.rand(1111,9999).'(未知)';
			$area = $this->returarea();
			$vm5 = 'sin:********'.rand(1111,9999).'(未知)';
			$area = $this->returarea();
			$vm6 = 'wx:********'.rand(1111,9999).'(未知)';
			$arr = array($vm1,$vm2,$vm3,$vm4,$vm5,$vm6);
			$key = rand(0,5);
			$username = $arr[$key];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'100元红包','mark'=>1,'time'=>date('Ymd')));
			
			$this->log('username:'.$username.'|100元红包|mark:2|time:'.date('Ymd'),$file);
		}
		$this->cnfol_filecache->set('windlist',$windList);
		/* 写入内存缓存 */
		$memcache = new Memcache;
		$memcache->pconnect('172.20.1.54',11211) or die("链接失败");
		$memcache ->set('hlwjr_hjmd_pcduankou',$windList, false, 864000);
		unset($windList);
	}
    /**
     * 日志记录
     * @param string $logInfo  日志内容
	 * @param string $logName  日志文件名
     * @return void
     */
    private function log($logInfo,$logName='system')
    {
		if(!empty($logInfo))
		{
			$info = '['.date('H:i:s').']['.$logInfo.']'.PHP_EOL;
			error_log($info,3,LOG_PATH.$logName.'_'.date('Ymd').'.log');
			//if(DEBUG === true) echo $info;
		}
    }
	/**
     * 地区概率
     * @param string $logInfo  日志内容
	 * @param string $logName  日志文件名
     * @return void
     */
	private function returarea()
	{
		/* 1是手机,2是25元红包,3是谢谢参与,4是10元话费,5是精美相框,6是100元红包,7是明日再来,8是50元红包 */
		$array_all = array(
			'福建'=>'7','上海'=>'80','浙江'=>'5','安徽'=>'1','江苏'=>'1','山东'=>'1',
			'广东'=>'800','广西'=>'5','海南'=>'1',
			'湖北'=>'5','湖南'=>'1','河南'=>'1','江西'=>'5',
			'北京'=>'5','天津'=>'1','河北'=>'5','内蒙古'=>'1',
			'宁夏'=>'1','新疆'=>'1','青海'=>'1','陕西'=>'1',
			'甘肃'=>'1','四川'=>'5','云南'=>'3','贵州'=>'1',
			'西藏'=>'1','重庆'=>'5','内蒙古'=>'1'
		);
		foreach($array_all as $key=>$value)
		{
			for($i=0;$i<$array_all[$key];$i++)
			{
				$alldata[] = $key;
			}
		}
		//error_log(print_r($alldata,true).'|'.date('d--His')."++".$alldata[array_rand($alldata)].PHP_EOL,3,LOG_PATH.'fraq.log');
		return $alldata[array_rand($alldata)];
		//unset($alldata);
	}
}