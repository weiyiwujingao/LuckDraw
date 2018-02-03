<?php
if(!defined('IN_CNFOL')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 手机版-前台默认控制器
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-7-27
 ****************************************************************/
class IndexHtml extends Controller
{
	/* 信息容器 */
	private $_config = array();
	private $_lang = array();

	function __construct()
    {
		/* 载入各页面配置信息 */
		$this->_config = $this->loadConfig('3gconfig','column');
		$this->_config['seo'] = $this->loadConfig('seoconfig','seo');
		/* 全局语言配置,详见config/langconfig.php */
		$this->_lang = $this->loadConfig('langconfig','3gmsginfo');
    }

	/**
	 * 前台首页
	 * @return view
	 */
    public function index()
    {
		$result['seo'] = $this->_config['seo']['index'];

		$this->loadView('_common/header3g.html',$result);
        $this->loadView('3g/index.html');
		$this->loadView('_common/footer3g.html');
    }

	/**
	 * 前台列表页
	 * @param int 标签ID
	 * @return view
	 */
    public function columnList($tagid)
    {
		if(is_numeric($tagid) && $tagid > 0)
		{
			$tagid = intval($tagid);
			if(isset($this->_config[$tagid]))
			{
				$result['seo'] = $this->_config['seo']['zsg'];

				$result['colname'] = $this->_config[$tagid]['colname'];
				$result['tagname'] = $this->_config[$tagid]['tagname'];

				$this->loadView('_common/header3g.html',$result);
				$this->loadView('3g/columnlist.html',$result);
				$this->loadView('_common/footer3g.html');
			}
		}
    }

	/**
	 * 前台有奖竞猜页
	 * @return view
	 */
	public function yjjc()
	{
		/* 读取后台上传的cvs文件内容 */
		$item = @file_get_contents(CACHE_PATH.'item.cache');
		
		if(!empty($item))
		{
			$itemArr = unserialize($item);
			foreach($itemArr as $class => $itemVal)
				$result['item'][] = $itemArr[$class][array_rand($itemArr[$class],1)];
			/* 分类4随机两题 */
			$result['item'][] =  $itemArr[4][array_rand($itemArr[4],1)];
		}
		//$result['item'] = array_slice($result['item'],0,10);
		$result['key'] = $this->getKey();
		$result['seo'] = $this->_config['seo']['yjwd'];
		//$result['item'] = array_slice($result['item'],0,2);
		$this->loadView('_common/header3g.html',$result);
        $this->loadView('3g/yjjc.html',$result);
		$this->loadView('_common/footer3g.html');
	}

	/**
	 * 前台获奖名单页
	 * @return view
	 */
	public function hjmd()
    {
		$result['seo'] = $this->_config['seo']['hjmd'];

		$cache = $this->loadClass('filecache');

		$windList = $cache->get('windlist');

		if(!empty($windList) && is_array($windList))
		{
			//$total = count($windList);
			/* 将手机版记录进行汇总 */
			$list  = array();
			foreach($windList as $key => $windVal)
			{
				if($windVal['mark'] == '1') continue;

				$list[$key]['phone'] = $windVal['phone'];
				$list[$key]['giftName'] = $windVal['giftName'];
				//$list[$key]['time'] = date('m-d',strtotime($windVal['time']));
			}
			/* 处理ajax请求 */
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
			{
				$num = ($this->get_gp('num') >= 10) ? intval($this->get_gp('num')) : 10;
				$this->returnJson(array('data'=>array_slice($list,$num,10),'flag'=>'00'));
			}
			
			$result['data'] = array_slice($list,0,10);
			$result['nowTime'] = date('Y年m月d日');

			$this->loadView('_common/header3g.html',$result);
			$this->loadView('3g/hjmd.html',$result);
			$this->loadView('_common/footer3g.html');
		}
    }

	/**
	 * 前台抽奖页
	 * @param int t 时间戳
	 * @param string keys 通讯密钥
	 * @return json
	 * @return view
	 */
	public function cj()
	{exit('该活动已结束!');
		if($this->checkKey() && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],M_BASE_URL.'yjjc.html') !== false)
		{
			$result['time'] = TIMES;
			$result['key']  = $this->getKey();
			$result['seo']  = $this->_config['seo']['cj'];

			$this->loadView('_common/header3g.html',$result);
			$this->loadView('3g/cj.html',$result);
			$this->loadView('_common/footer3g.html');
		}
		else
		{
			header('location:'.M_BASE_URL.'yjjc.html');
		}
	}

	/**
	 * 获取礼物ID
	 * @param string phone 手机号码
	 * @return json
	 */
	public function getGiftID()
	{exit('该活动已结束!');
		$phone  = $this->get_gp('phone');

		if($this->checkKey() && strlen($phone) == 11 && preg_match('/^1[0-9]{10}$/',$phone) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
		{
			$info = $giftID = '';
			$user = $gift = array();
			
			$cache = $this->loadClass('filecache');
			
			$user = $cache->get('userlist');
			/* 初始化用户数据 */
			if(!isset($user[$phone]))
			{
				$cache->set('userlist',$this->initUser($phone,$user));
			}
			/* 异常情况,直接退回 */
			if(!isset($user[$phone]) || !isset($user[$phone]['3gcount']) || !isset($user[$phone]['3gitem']))
				$this->returnJson(array('info'=>$this->_lang[0],'flag'=>'97'));

			/* 没有抽奖次数,直接返回 */
			if($user[$phone]['3gcount'] <= 0)
				$this->returnJson(array('info'=>$this->_lang[3],'count'=>$user[$phone]['3gcount'],'flag'=>'3'));

			$gift = $cache->get('3g_giftlist');

			if(!empty($gift) && is_array($gift))
			{
				$giftID = isset($user[$phone]['3gitem'][0]) ? 3 : array_rand($gift);
				//$giftID = isset($gift[$giftID]) ? $giftID : 3;
				/* 处理抽到已经不存在的奖品情况 */
				/*
				if(!isset($gift[$giftID]))
				{
					while(true)
					{
						$giftID = array_rand($gift);
						if(isset($gift[$giftID])) break;
					}
				}*/

				/* 未中奖及异常情况不再往下执行,直接返回 */
				$tmpTime = $cache->get('tmplist');
				if($giftID == 3 || !isset($gift[$giftID]) || ($gift[$giftID]['count']-1) < 0 || (time() - $tmpTime) < 600)
				{
					$user[$phone]['3gcount'] -= 1;
					$cache->set('userlist',$user);

					$info = ($user[$phone]['3gcount'] > 0) ? sprintf($this->_lang[2],$user[$phone]['3gcount']) : $this->_lang[3];

					$this->returnJson(array('info'=>$info,'count'=>$user[$phone]['3gcount'],'flag'=>'2'));
				}

				/* 更新当天奖品列表剩余总数 */
				//if(($gift[$giftID]['count']-1) > 0)
					//$gift[$giftID]['count'] -= 1;
				//else
					//unset($gift[$giftID]);

				/* 更新用户剩余抽奖次数及奖品 */
				$gift[$giftID]['count']  -= 1;
				$user[$phone]['3gcount'] -= 1;
				$user[$phone]['3gitem'][] = $giftID;

				$cache->set('userlist',$user);			
				$cache->set('3g_giftlist',$gift);

				$usermodel = $this->loadModel('usermodel');
				/* 对应后台表giftid关联 */
				$gift_type = array(1=>12,2=>13,4=>14,6=>11);
				$bindVal = array('aid'=>1,'gid'=>$gift_type[$giftID],'phone'=>$phone,'source'=>2,'upTime'=>time());
				$usermodel->insertUser($bindVal);
				//跟踪日志1
				error_log(date('H:i:s').PHP_EOL.print_r($bindVal,true).PHP_EOL.'-----------'.PHP_EOL,3,LOG_PATH . 'jraq_sql'.date('Ymd').'.log');

				/* 获取获奖列表,详情cache/cache_windlist */
				$windList = $cache->get('windlist');

				/* 更新获奖列表 mark:1 PC版  mark:2 手机版 */
				$strPhone = substr($phone,0,7).'****';
				if(!empty($windList) && is_array($windList))
					array_unshift($windList,array('phone'=>$strPhone,'giftName'=>$gift[$giftID]['giftname'],'mark'=>2,'time'=>date('Ymd')));
				else
					$windList[] = array('phone'=>$strPhone,'giftName'=>$gift[$giftID]['giftname'],'mark'=>2,'time'=>date('Ymd'));

				//跟踪日志2
				error_log(date('H:i:s').PHP_EOL.'phone:'.$strPhone.'~'.$phone.'|giftname:'.$gift[$giftID]['giftname'].'|mark:2|time:'.date('Ymd').PHP_EOL.'-----------'.PHP_EOL,3,LOG_PATH . 'jraq_user_'.date('Ymd').'.log');

				/* 将最新获奖信息写入缓存,用于抽奖页滚动 */
				$cache->set('windlist',$windList);

				unset($windList);

				$info = ($user[$phone]['3gcount'] > 0) ? sprintf($this->_lang[1],$gift[$giftID]['giftname'],$user[$phone]['3gcount']) : sprintf($this->_lang[4],$gift[$giftID]['giftname']);

				$cache->set('tmplist',$windList['time']=time());

				$this->returnJson(array('info'=>$info,'giftid'=>$giftID,'count'=>$user[$phone]['3gcount'],'flag'=>'1'));
			}
			else
			{
				$this->returnJson(array('flag'=>'99'));
			}
		}
		else
		{
			$this->returnJson(array('flag'=>'98'));
		}
	}

	/**
	 * 初始化用户数据
	 * @param string $phone 手机号码
	 * @param array  $user  用户数据
	 * @return array
	 */
	private function initUser($phone,&$user)
	{
		$user[$phone]['pccount'] = 2;
		$user[$phone]['pcitem']  = array();
		$user[$phone]['3gcount'] = 2;
		$user[$phone]['3gitem']  = array();

		return $user;
	}

	/**
	 * 计算密钥
	 * @param int t  时间戳
	 * @param string key 密钥
	 * @return json
	 */
	private function checkKey()
	{
		$time = $this->get_gp('t');
		$key  = $this->get_gp('key');
		$keys = substr(md5(md5(KEYS).md5(substr($time,-1,9))),0,14);

		return ($key == $keys) ? true : false;
	}

	/**
	 * 计算密钥
	 * @return json
	 */
	private function getKey()
	{
		return substr(md5(md5(KEYS).md5(substr(TIMES,-1,9))),0,14);
	}

	/**
	 * 返回JSON
	 * @return json
	 */
	private function returnJson($info)
	{
		exit(json_encode($info));
	}
}