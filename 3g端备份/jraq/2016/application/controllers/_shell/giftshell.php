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

		$phone = '';
		$phoneArr = array(130,131,132,133,134,135,136,137,138,139,150,151,152,153,155,156,157,158,159,186,183,187,188,189);
		

		$windList = $this->cnfol_filecache->get('windlist');
		if($REQUEST['flag']=='lj')
		{
			//获奖的虚拟手机
			$vm1 = '150****6801(福建)';
			$arr = array($vm1);
			$username = $arr[0];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'iphone6s','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|iphone6s|mark:1|time:'.date('Ymd'),$file);
		}
		if($REQUEST['flag']=='wjg')
		{
			//获奖的虚拟手机
			$vm1 = '186****0576(福建)';
			$arr = array($vm1);
			$username = $arr[0];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'iphone6s','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|iphone6s|mark:1|time:'.date('Ymd'),$file);
		}
		if($REQUEST['flag']=='zmw')
		{
			//获奖的虚拟手机
			$vm1 = '180****1127(福建)';
			$arr = array($vm1);
			$username = $arr[0];
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'iphone6s','mark'=>1,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|iphone6s|mark:1|time:'.date('Ymd'),$file);
		}
		if($date >= '0800' && $date <= '0900')
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
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'25元红包','mark'=>2,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|25元红包|mark:2|time:'.date('Ymd'),$file);

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
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'10元话费','mark'=>2,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|10元话费|mark:2|time:'.date('Ymd'),$file);

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
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'10元话费','mark'=>2,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|10元话费|mark:2|time:'.date('Ymd'),$file);

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
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'10元话费','mark'=>2,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|10元话费|mark:2|time:'.date('Ymd'),$file);

		}
		if($date >= '1000' && $date <= '2000')
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
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'精美相框','mark'=>2,'time'=>date('Ymd')));
			//获奖的虚拟用户名
			$this->log('phone:'.$phone.'|精美相框|mark:2|time:'.date('Ymd'),$file);

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
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'50元红包','mark'=>2,'time'=>date('Ymd')));
			
			$this->log('username:'.$username.'|50元红包|mark:2|time:'.date('Ymd'),$file);

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
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'100元红包','mark'=>2,'time'=>date('Ymd')));
			
			$this->log('username:'.$username.'|100元红包|mark:2|time:'.date('Ymd'),$file);
		}
		if($date >= '0900' && $date <= '1000')
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
			array_unshift($windList,array('username'=>$username,'phone'=>'','giftName'=>'100元红包','mark'=>2,'time'=>date('Ymd')));
			
			$this->log('username:'.$username.'|100元红包|mark:2|time:'.date('Ymd'),$file);
		}
		$this->cnfol_filecache->set('windlist',$windList);
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
			'福建'=>'70','上海'=>'10','浙江'=>'5','安徽'=>'0','江苏'=>'0','山东'=>'0',
			'广东'=>'10','广西'=>'5','海南'=>'0',
			'湖北'=>'5','湖南'=>'0','河南'=>'0','江西'=>'5',
			'北京'=>'5','天津'=>'0','河北'=>'5','内蒙古'=>'0',
			'宁夏'=>'0','新疆'=>'0','青海'=>'0','陕西'=>'0',
			'甘肃'=>'0','四川'=>'5','云南'=>'0','贵州'=>'0',
			'西藏'=>'0','重庆'=>'0','内蒙古'=>'0'
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