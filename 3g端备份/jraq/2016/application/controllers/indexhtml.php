<?php
if(! defined('BASEPATH')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 PC版-前台默认控制器
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class Indexhtml extends MY_Controller
{
	/* seo信息容器 */
	private $_config = array();
	/* 语言信息容器 */
	private $_lang   = array();


	function __construct()
    {
		 parent::__construct();
		 error_reporting(0);
		 header("Content-type: text/html; charset=utf-8");
		/* 载入各页面seo信息,详见config/seoconfig.php */
		$this->_config = $this->config->item('seo');
		/* 全局语言配置,详见config/langconfig.php */
		$this->_lang = $this->config->item('pcmsginfo');
		/* 加载url辅助函数 */
		$this->load->helper('url');
		/* 加载获取GET或者POST的参数值类,文件缓存类 */
		$this->load->library(array('CNFOL_Filter','CNFOL_FileCache'));
		/* 加载获奖用户模型 */
		$this->load->model('usergift_model');
    }

	/**
	 * 前台首页
	 * @return view
	 */
    public function index()
    {
		$areauser = array();
		/* 获取手机网的获奖名单 */
		//$windlistphonestr = file_get_contents('http://jraq.cnfol.com/2016/api/windlists.html');
		//$windlistphonearr = json_decode($windlistphonestr,true);
		/* 本地获奖名单 */
		//$windList = $this->cnfol_filecache->get('windlist');
		/* 合计获奖名单 */
		//$windList = array_merge($windList,$windlistphonearr['list']);
		/*foreach($windList as $k=>$v)
		{
			if(!empty($windList[$k]['phone']))
				$areauser[] = $windList[$k];
		}*/
		/*$arr1 = $arr2 = $arr3 = array();
		$memcache = new Memcache;
		$ret = $memcache->pconnect('172.20.1.54',11211) or die("链接失败");
		$arr1 = $memcache ->get('hlwjr_hjmd_pcduankou');
		$arr2 = $memcache ->get('hlwjr_hjmd_3g_160duankou');
		$arr3 = $memcache ->get('hlwjr_hjmd_3g_99duankou');
		if(is_array($arr1)&&is_array($arr2)&&is_array($arr3))
		{	
			$windList = array_merge($arr1,$arr2,$arr3);
		}else{
			$windList = $this->cnfol_filecache->get('windlist');
		}
		$alldata = count($windList);
		$fjdata = $gddata = $shdata = $qtsata = '0';  
		foreach($windList as $key=>$value)
		{
			if(strpos($windList[$key]['username'],'福建')) $fjdata += 1;
			if(strpos($windList[$key]['username'],'广东')) $gddata += 1;
			if(strpos($windList[$key]['username'],'上海')) $shdata += 1;
		}
		$fjdata = sprintf('%.1f',$fjdata/$alldata)*100;
		$gddata = sprintf('%.1f',$gddata/$alldata)*100;
		$shdata = sprintf('%.1f',$shdata/$alldata)*100;
		$qtdata = 100-$fjdata-$shdata-$gddata;*/
		$areabili = $this->areabili();
		$fjdata = $areabili['fj'];
		$gddata = $areabili['gd'];
		$shdata = $areabili['sh'];
		$qtdata = $areabili['qt'];
		$result['fjdata'] = $fjdata;
		$result['gddata'] = $gddata;
		$result['shdata'] = $shdata;
		$result['qtdata'] = $qtdata;
		//var_dump($result);exit;
		$result['seo'] = $this->_config['index'];
		//$this->load->view('_common/header.html',$result);
		//$this->load->view('_common/toplogin.html',$result);
		//$this->load->view('index.html');		
		$this->load->view('shouye.html',$result);
		//$this->load->view('_common/footer.html');
		
    }
	/**
	 * 前台活动介绍页
	 * @return view
	 */
	public function hdjs()
    {
		$result['seo'] = $this->_config['hdjs'];

		//$this->load->view('_common/header.html',$result);
		//$this->load->view('_common/toplogin.html',$result);
		$this->load->view('hdjs.html',$result);
        //$this->loadView('hdhx.html');
		//$this->load->view('_common/footer.html');;
    }
	/**
	 * 前台有奖竞猜页
	 * @return view
	 */
	public function yjwd()
	{	
		//活动自动停止脚本
	//	$dateexit = date('Y-m-d');
	//	if($dateexit != '2016-03-31'){
			$url = 'http://m.3g.cnfol.com/';
			echo "<script language='javascript'>alert('有奖答题活动已结束，感谢关注！');window.location='$url';</script>";
		    exit;
	//	}

		/* 读取后台上传的cvs文件内容 */
		$item = @file_get_contents(CACHE_PATH.'2016hlwwd.cache');
		if(!empty($item))
		{
			$itemArr = unserialize($item);
			//error_log(print_r($itemArr,true).PHP_EOL,3,APPPATH.'logs/itemarr.log');
			//$itemRandArr = array_rand($itemArr,10);
			foreach($itemArr as $class => $itemVal)
				$result['item'][] = $itemArr[$class][array_rand($itemArr[$class],1)];
			/* 分类4随机两题 */
			//$result['item'][] =  $itemArr[4][array_rand($itemArr[4],1)];
			//array_pop($result['item']);
		}
		//var_dump($result['item']);exit;
		$result['time'] = TIMES;
		$result['key']  = $this->getKey();
		$result['seo']  = $this->_config['yjwd'];
		//$result['item'] = $result;
		$result['item'] = $result['item'];
		//$result['item'] = array_slice($result['item'],0,2);
		$result['item'] = $result['item'];
		//$this->load->view('_common/header.html',$result);
		//$this->load->view('_common/toplogin.html',$result);
        $this->load->view('yjwd.html',$result);
		//$this->load->view('_common/footer.html');
	}
	/**
	 * 前台抽奖页
	 * @return view
	 */
	public function cj()
	{   exit('竞猜活动已结束');
		//活动自动停止脚本
		$dateexit = date('Y-m-d');
		if($dateexit != '2016-03-31')exit('竞猜活动已结束');
		/* 答题验证 */
		$cookie_name = $this->input->cookie('cookie_name');
		$yhjd = intval($this->input->cookie('yhjd'));
		$key  = $this->cnfol_filter->get_gp('key');
		if($cookie_name != $key || (time()-$yhjd) > '1200'){header('location:'.base_url());}
		/* 未登录的用户名初始化 */
		$this->data['userid']   = 0;
		$this->data['username'] = '0';
		/* 判断是否已答题 */	
		if($this->checkKey())
		{
			if($this->input->cookie('a_k'))
			{
				/* 解密cookie中加密用户信息 */
				$ak = $this->input->cookie('a_k');
				$tk = intval($this->input->cookie('t_k'));

				$this->data['userid'] = 0;
				$this->data['username'] = '';
				if($ak && $tk > 0)
				{
					/* 解密加密信息 */
					$pk = authcode($ak, 'DECODE', md5('E3#n' . substr($tk, -3)));
					/* 加密内容格式 userid|_|username */
					$pk = explode('|_|', $pk);

					if($pk && count($pk) == 2)
					{
						$userid   = $pk[0];
						$username = $pk[1];
						
						$this->data['userid']   = $userid;
						$this->data['username'] = $username;
						/* 重写通讯密钥 */
						//$this->data['keys'] = $this->get_secret_key($userid . $keys[1]);
					}
				}
				if(!empty($this->data['username']))
				{
					/* 获取用户列表,详见cache/cache_userlist */
					$memcache = new Memcache;
					$memcache->pconnect('172.20.1.54',11211);
					//$user = $memcache ->get('hlwjr_user_init_3g_99md'.date('ymd'));
					//if(empty($user)){$user = $this->cnfol_filecache->get('userlist');}
					$user = $this->cnfol_filecache->get('userlist');
					/* 异常情况,直接返回 */
					if(!isset($user[$this->data['username']]))
					{
						/* 初始化用户 */
						$this->cnfol_filecache->set('userlist',$this->initUsermessenger($this->data['username'],$user));
						$memcache ->set('hlwjr_user_init_3g_99md'.date('ymd'),$this->initUsermessenger($this->data['username'],$user), false, 864000);
					}
				}
			}

			$this->data['time']  = TIMES;
			$this->data['key']   = $this->getKey();
			//$this->data['phone'] = $phone;
			$this->data['seo']   = $this->_config['cj'];
			/* 手机端无需获奖名单 */
		//	$this->data['windList']	= $this->cnfol_filecache->get('windlist');

			 /* 168登录链接 */
            $time = time();
            $keys = md5(md5('Lc1N6f8o') . $time);
			$keytime = $this->cnfol_filter->get_gp('t');
		    $keykeys  = $this->cnfol_filter->get_gp('key');
            $callback = urlencode(CALLBACK_168URL.'?rt='.base64_encode(urlencode('http://m.3g.cnfol.com/jraq/2016/cj.html?t='.$keytime.'&key='.$keykeys)));
			//var_dump(urldecode($callback));var_dump(get_url().'?t='.$keytime.'&key='.$keykeys);//exit;

			/* 168手机登录链接 */
            $this->data['mLogin']   = sprintf(LOGIN_168URL2, 'phone', $keys, $time, $callback);
			//var_dump($this->data['mLogin']);exit;

			/* 168第三方登录链接 */
            $this->data['zjLogin']   = sprintf(LOGIN_168URL, 'zj', $keys, $time, $callback);
			$this->data['wxLogin']   = sprintf(LOGIN_168URL, 'wx', $keys, $time, $callback);
            $this->data['qqLogin']   = sprintf(LOGIN_168URL, 'qq', $keys, $time, $callback);
            $this->data['sinaLogin'] = sprintf(LOGIN_168URL, 'sina', $keys, $time, $callback);

			/* 回调后168那边的错误信息 */
			$this->data['callback_error'] = urldecode($this->input->get('error', TRUE));

			//$this->load->view('_common/header.html',$this->data);
			//$this->load->view('_common/toplogin.html',$this->data);
			$this->load->view('cj.html',$this->data);
			//$this->load->view('_common/footer.html');
		}
		else
		{
			header('location:'.base_url());
		}
	}
	/**
	 * 前台问卷调查页
	 * @return view
	 */
	public function wjdc()
    {
		//$result['seo'] = $this->_config['wjtz'];

		//$this->load->view('_common/header.html',$result);
	//	$this->load->view('_common/toplogin.html',$result);
        $this->load->view('wjdc.html',$result);
		//$this->load->view('_common/footer.html');
    }
	/**
	 * 前台知识馆页
	 * @return view
	 */
	public function zsg()
    {
		$result['seo'] = $this->_config['zsg'];

		//$this->load->view('_common/header.html',$result);
		//$this->load->view('_common/toplogin.html',$result);
        $this->load->view('zsg.html',$result);
		//$this->load->view('_common/footer.html');
    }
	/**
	 * 前台获奖名单页
	 * @return view
	 */
	public function hjmd()
    {
		$result['seo'] = $this->_config['hjmd'];

		//$this->load->library('CNFOL_FileCache');
		/* 获取手机网的获奖名单 */
		//$windlistphonestr = file_get_contents('http://jraq.cnfol.com/2016/api/windlists.html');
		//$windlistphonearr = json_decode($windlistphonestr,true);
		/* 本地获奖名单 */
		//$windList = $this->cnfol_filecache->get('windlist');
		/* 合计获奖名单 */
		//$windList = array_merge($windList,$windlistphonearr['list']);
		$arr1 = $arr2 = $arr3 = array();
		$memcache = new Memcache;
		$ret = $memcache->pconnect('172.20.1.54',11211) or die("链接失败");
		$arr1 = $memcache ->get('hlwjr_hjmd_pcduankou');
		$arr2 = $memcache ->get('hlwjr_hjmd_3g_160duankou');
		//$arr3 = $memcache ->get('hlwjr_hjmd_3g_99duankou');
		if(is_array($arr1)&&is_array($arr2)	)
		{	
			$windList = array_merge($arr2,$arr1);
		}else{
			$windList = $this->cnfol_filecache->get('windlist');
		}
		if(!empty($windList) && is_array($windList))
		{
			$pageer = $this->load->library('CNFOL_Pager');

			$this->cnfol_pager->conf['select'] = 0;
			$this->cnfol_pager->conf['total_num'] = 0;

			$page = ($this->cnfol_filter->get_gp('page') > 0) ? intval($this->cnfol_filter->get_gp('page')) : 1;
			/* 将PC版记录进行汇总 */
			$count = 0;
			$list  = array();
			foreach($windList as $key => $windVal)
			{
				if($windList[$key]['time'] == date('Ymd')){
					$listnow[$key]['username'] = $windList[$key]['username'];
					$listnow[$key]['giftName'] = $windList[$key]['giftName'];
					$listnow[$key]['time'] = date('m-d',strtotime($windList[$key]['time']));
					continue;
				} 
				$list[$key]['username'] = $windList[$key]['username'];
				$list[$key]['giftName'] = $windList[$key]['giftName'];
				$list[$key]['time'] = date('m-d',strtotime($windList[$key]['time']));
			}
			if(is_array($listnow) && !empty($listnow)){
				$list = array_merge($listnow,$list);
			}
			$total = count($list);
			/* 索引重置 */
			$list = array_values($list);
			$linkStr = base_url()."hjmd.html";
			
			$result['pageLink'] = $this->cnfol_pager->pager($total,30,$linkStr);
			$result['data'] = array_slice($list,0,30*($page));
			$result['nowPage'] = $page;
		}
		//$this->load->view('_common/header.html',$result);
		//$this->load->view('_common/toplogin.html',$result);
		$this->load->view('hjmd.html',$result);
		//$this->load->view('_common/footer.html');
    }
	/**
	 * 获取中奖礼物
	 * @param string username 用户名
	 * @return json
	 */
	public function getnewgift()
	{exit('竞猜活动已结束');
		//活动自动停止脚本
		$dateexit = date('Y-m-d');
		if($dateexit != '2016-03-31')exit('竞猜活动已结束');
		/* 写入内存缓存 */
		$memcache = new Memcache;
		$memcache->pconnect('172.20.1.54',11211);
		/* 168理财红包数组，giftid值 */
		$licaiArr = array('2','6','8');
		/* 已经登录过的登录名和id */
		$username= $this->cnfol_filter->get_gp('username');
		$userid = intval($this->cnfol_filter->get_gp('userid'));
		
		if(empty($username)||empty($userid)){
			$this->returnJson(array('flag'=>"3"));
			@error_log('获取用户名和用户id失败'.date('Y-m-d H:i:s'),3,LOG_PATH.'usernameandid_'.date('Ymd').'log');
		}
		/* 身份验证 */
		if($this->checkKey() && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],base_url().'cj.html') !== false && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
		{   
			$info = $giftID = '';
			$user = $gift = array();

			/* 获取用户列表,详见cache/cache_userlist */
			$user = $this->cnfol_filecache->get('userlist');
			/* 调整为内存缓存 */
			//$memcache = new Memcache;
			//$memcache->pconnect('172.20.1.54',11211);
		   // $user1 = $memcache ->get('hlwjr_user_init_3g_160md'.date('ymd'));
		  //  $user = $memcache ->get('hlwjr_user_init_3g_99md'.date('ymd'));
			//if(empty($user)){$user = $this->cnfol_filecache->get('userlist');}
		   // if(!empty($user2)){ $user = array_merge($user1,$user2); }else{$user = $user1;}
			
			/* 异常情况,直接返回 */
			if(!isset($user[$username]) || !isset($user[$username]['pccount']) || !isset($user[$username]['pcitem']))
			{
				$this->returnJson(array('flag'=>'3'));
				@error_log('初始化用户失败'.date('Y-m-d H:i:s'),3,LOG_PATH.'chushihuauser_'.date('Ymd').'log');
			}
			//$this->returnJson(array('flag'=>'5'));
			/* 判断机会是否已经用完 */
			if($user[$username]['pccount'] <= 0)
			{
				/* 如果2次抽奖机会用完了直接返回 */
				$this->returnJson(array('flag'=>'7'));
			}

			/* 正常的抽奖流程开始 */

			/* 获取当天奖品列表,详见cache/cache_pc_giftlist */
			$gift = $this->cnfol_filecache->get('pc_giftlist');

			if(!empty($gift) && is_array($gift))
			{	
				
				/* 随机奖品列表,如果当天已中过奖直接设置未中奖 3:未中奖 */
				$giftID = isset($user[$username]['pcitem'][0]) ? 3 : array_rand($gift);
				//error_log(date('Y-m-d_H:i:s').PHP_EOL.print_r($gift,true).PHP_EOL,3,LOG_PATH . 'jraqs.log');
				/* 设置中奖概率 */
				$giftID = isset($user[$username]['pcitem'][0]) ? 3 : $this->returnintval();

				/* 最近获奖时间，$time为现在时间和最近时间的距离 */
				$tmpTime = $this->cnfol_filecache->get('tmplist');
				$timeup = time() - $tmpTime;
				$useridgift = $this->usergift_model->selectuser($username);
				if(empty($useridgift)){
					$giftID = isset($user[$username]['pcitem'][0]) ? 3 : 2;
					$timeup = '2000';
				}
				/* 未中奖及异常情况不再往下执行,直接返回 */
				if($giftID == 3 || !isset($gift[$giftID]) || ($gift[$giftID]['count']-1) < 0 || $timeup < 500)
				{
					//$this->returnJson(array('flag'=>"1"));
					$cishu = $user[$username]['pccount'];
					/* 抽奖次数减去一 */
					$user[$username]['pccount'] -= 1;//$user[$phone]['pccount']=6;
					/* 重置用户抽奖信息 */
					$this->cnfol_filecache->set('userlist',$user);
					$memcache ->set('hlwjr_user_init_3g_99md'.date('ymd'),$user, false, 86400);
					/* 还有抽奖次数 */
					if($cishu > 0)
						$this->returnJson(array('flag'=>'3'));
					else
						/* 没有抽奖次数的情况 */
						$this->returnJson(array('flag'=>'7'));
				}

				/* 获奖处理 */

				/* 更新用户剩余抽奖次数及奖品 */
				$gift[$giftID]['count']  -= 1;
				$user[$username]['pccount'] -= 1;
				$user[$username]['pcitem'][] = $giftID;

				/* 更新用户列表 */
				$this->cnfol_filecache->set('userlist',$user);
				$memcache ->set('hlwjr_user_init_3g_99md'.date('ymd'),$user, false, 86400);
				/* 更新礼物列表 */
				$this->cnfol_filecache->set('pc_giftlist',$gift);

				/* 对应后台表giftid关联 */
				$bindVal   = array('aid'=>2,'gid'=>"$giftID",'username'=>$username,'source'=>1,'upTime'=>time());
				@error_log(date('Y-m-d_H:i:s').PHP_EOL.print_r($bindVal,true).PHP_EOL,3,LOG_PATH . 'jraqs.log');
				/* 获奖记录入库一份 */
				$this->usergift_model->insertUser($bindVal);
				
				$this->cnfol_filecache->set('tmplist',time());

				/* 跟踪日志1 */
				@error_log(date('md_H:i:s').PHP_EOL.print_r($bindVal,true).PHP_EOL.'-----------'.PHP_EOL,3,LOG_PATH.'jraq_sql'.date('Ymd').'.log');
				$strPhone = str_repace($username);
				@error_log(date('Y-m-d_H:i:s').PHP_EOL.print_r($strPhone,true).PHP_EOL,3,LOG_PATH . 'jraqs.log');
				
				//$this->returnJson(array('flag'=>"$giftID"));

				/* 获取获奖列表,详情cache/cache_windlist */
				$windList = $this->cnfol_filecache->get('windlist');

				/* 更新获奖列表 mark:1 PC版  mark:2 手机版 */
				$strPhone = str_repace($username);
				/* 地区 */
				$area = $this->phonearea($username);
				if(!empty($windList) && is_array($windList))
					/* 已有获奖名单，插入新的获奖名单，从0元素开始 */
					array_unshift($windList,array('username'=>$strPhone.'('.$area.')','phone'=>'','giftName'=>$gift[$giftID]['giftname'],'mark'=>2,'time'=>date('Ymd')));
				else
					$windList[] = array('username'=>$strPhone.'('.$area.')','phone'=>$strPhone,'giftName'=>$gift[$giftID]['giftname'],',mark'=>2,'time'=>date('Ymd'));

				//跟踪日志2
				@error_log(date('H:i:s').PHP_EOL.'username:'.$strPhone.'~'.$username.'|giftname:'.$gift[$giftID]['giftname'].'|mark:2|time:'.date('Ymd').PHP_EOL.'-----------'.PHP_EOL,3,LOG_PATH.'jraq_user_'.date('Ymd').'.log');
				

				/* 判断如果是理财红包的话发送接口到168网 */
			    if(in_array($giftID,$licaiArr))
			    {
					/* 是否获取红包成功,不成功就未中奖 */
					$param['userid'] = $userid;
					$param['giftid'] = $gift[$giftID]['giftid'];
					//exit(json_encode(array('flag'=>"5")));
					/* 如果发送红包失败直接返回谢谢到前端 */
					if($this->usergift_model->get_redbag($param)!='1')
						$this->returnJson(array('flag'=>"3"));
				}
				/* 将最新获奖信息写入缓存,用于抽奖页滚动 */
				$this->cnfol_filecache->set('windlist',$windList);
				$memcache ->set('hlwjr_hjmd_3g_160duankou',$windList, false, 864000);
				unset($windList);
				
			}
			$this->returnJson(array('flag'=>"$giftID"));
		}else{
			$this->returnJson(array('flag'=>'3'));
		}
	}	
	/**
	 * 抽中话费处理 
	 */
	 public function getPhone()
	 {
		$phone = $this->cnfol_filter->get_gp('phone');
		$username = $this->cnfol_filter->get_gp('username');
		$strPhone = str_repace($username);
		$area = $this->phonearea($phone);
		$areausername = $this->phonearea($username);
		@error_log($phone.'||'.$username.'|'.date('ymd : H-i-s').PHP_EOL,3,'/var/tmp/jjjg.log');
		if($this->checkKey() && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],base_url().'cj.html') !== false && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'&&isset($username))
		{
			//$this->load->model('usergift_model');
		    $this->usergift_model->updatetUser($phone,$area,$username);
			/* 获取获奖列表,详情cache/cache_windlist */
			$windList = $this->cnfol_filecache->get('windlist');
			foreach($windList as $key=>$value)
			{
				if($strPhone.'('.$areausername.')'==$windList[$key]['username']){
					$windList[$key]['phone'] = $phone;
					//$windList[$key]['username'] = $windList[$key]['username'].'('.$area.')';
					$windList[$key]['username'] = $strPhone.'('.$area.')';
					 //continue 1;
					 break;
				}
			}
			/* 将最新获奖信息写入缓存,用于抽奖页滚动 */
			$this->cnfol_filecache->set('windlist',$windList);
		}
	 }
	/**
	 * 获取中奖列表
	 * @return json
	 */
	public function getWindList()
	{
		$phone = $this->get_gp('phone');

		if($this->checkKey() && strlen($phone) == 11 && preg_match('/^1[0-9]{10}$/',$phone) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
		{
			$this->returnJson(array('list'=>$this->cnfol_filecache->get('windlist'),'flag'=>'00'));
		}
		else
		{
			$this->returnJson(array('flag'=>'11'));
		}
	}
    /**
	 * 获取中奖列表
	 * @return json
	 */
	public function getWindLists()
	{
		$this->returnJson(array('list'=>$this->cnfol_filecache->get('windlist')));
	}
	/**
	 * 设置摇奖次数,分享功能
	 * @param string phone 手机号码
	 * @param int t 时间戳
	 * @param string keys 通讯密钥
	 * @return json
	 */
	public function setErnieCount()
	{
		
		$phone = $this->cnfol_filter->get_gp('phone');
		error_log('fenxiang'.$phone.date('d-H:i:s').PHP_EOL,3,LOG_PATH.'fraq.log');
		
		//$state  = intval($this->get_gp('s'));
		//$giftID = intval($this->get_gp('g'));

		if(strlen($phone) == 11 && preg_match('/^1[0-9]{10}$/',$phone) && $this->checkKey() && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
		{
			$user = array();			
			$user  = $this->cnfol_filecache->get('userlist');

			/* 非法情况,直接退回 */
			if(!isset($user[$phone]) || !isset($user[$phone]['pcisshare']))
				$this->returnJson(array('flag'=>'22'));
			/* 判断是否已设置过次数 */
			if($user[$phone]['pcisshare'])
				$this->returnJson(array('flag'=>'33'));

			$user[$phone]['pcisshare'] = true;
			$user[$phone]['pccount'] += 1;
			$this->cnfol_filecache->set('userlist',$user);

			/* 获取当天奖品列表,详见cache/cache_pc_giftlist */
			$info = array();
			/* 这里主要是处理点击分享后的提示框信息 */
			
			$this->returnJson(array('info'=>sprintf($this->_lang[9],$user[$phone]['pccount']),'flag'=>'00'));
		}
		else
		{
			$this->returnJson(array('flag'=>'11'));
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
		$user[$phone]['pcisshare'] = false;

		return $user;
	}
	
	/**
	 * 初始化用户数据
	 * @param string $username 用户名
	 * @param array  $user  用户数据
	 * @return array
	 */
	private function initUsermessenger($username,&$user)
	{
		$user[$username]['pccount'] = 2;
		$user[$username]['pcitem']  = array();
		$user[$username]['phone'] = '';
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
		$time = $this->cnfol_filter->get_gp('t');
		$key  = $this->cnfol_filter->get_gp('key');
		$keys = substr(md5(md5(KEYS).md5(substr($time,-1,9))),0,14);

		/* 判断时间是否过期 */
		if((time()-$time)>1200){
			return false;
		}

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
	 * 设计中奖的频率
	 * @return int 
	 */
	private function returnintval()
	{
		/* 1是手机,2是25元红包,3是谢谢参与,4是10元话费,5是精美相框,6是100元红包,7是明日再来,8是50元红包 */
		$array_all = array('2'=>'60','3'=>'5','4'=>'10','5'=>'20','6'=>'10','8'=>'20');
		foreach($array_all as $key=>$value)
		{
			for($i=0;$i<$array_all[$key];$i++)
			{
				$alldata[] = $key;
			}
		}
		//error_log(print_r($alldata,true).'|'.date('d--His')."++".$alldata[array_rand($alldata)].PHP_EOL,3,LOG_PATH.'fraq.log');
		return $alldata[array_rand($alldata)];
	}
	/* 判断电话来源地 */
	public function phonearea($phone)
	{
		$phonea =substr($phone,0,7);
		$rss = array();
		$rs = $this->usergift_model->selectmobile($phonea);
		if(isset($rs['0']['MobileArea']))
		{
			$rss = explode(' ',$rs['0']['MobileArea']);
		}else{
			$rss[0] = '未知';
		}
		$area = $rss[0];
		return $area;
	}
	/**
	 * 返回JSON
	 * @return json
	 */
	private function returnJson($info)
	{
		exit(json_encode($info));
	}
	/**
     * 168登录成功后跳转地址
     *
	 * @param string $rt 回调地址
	 * @param string $s  加密串
	 * @return void
     */
    public function callback_url()
	{
		$return = $this->input->get('rt', TRUE);
        $strkey = $this->input->get('s', TRUE);
        if($return && $strkey)
		{
        	/* strkey解密完格式为 $flag, $info, $userid, $username */
        	$data = explode(',', authcode($strkey));
        	/* 回调地址 */
        	$decodeurl = urldecode(base64_decode($return));
             //var_dump(urldecode($decodeurl));exit;
        	if(!empty($data) && count($data) == 4 )
			{
        		list($flag, $info, $userid, $username) = $data;
        		
        		unset($data);

        		if($flag == '0' && $info == '登录成功' && !empty($userid) && !empty($username))
				{
					$nowtime = time();
					
					$tkcookie = array(
                    		'name'   => 't_k',
                    		'value'  => $nowtime,
                    		'prefix' => config_item('cookie_prefix'),
                    		'domain' => config_item('cookie_domain'),
                    		'path'   => config_item('cookie_path'),
                    		'expire' => 30*60
                    );

                    $akcookie = array(
                    		'name'   => 'a_k',
                    		'value'  => authcode($userid . '|_|' . $username, 'ENCODE', md5('E3#n' . substr($nowtime, -3))),
                    		'prefix' => config_item('cookie_prefix'),
                    		'domain' => config_item('cookie_domain'),
                    		'path'   => config_item('cookie_path'),
                    		'expire' => 30*60
                    );
					///var_dump(authcode($akcookie['value'],'DECODE'),md5('E3#n' . substr($nowtime, -3)));exit;
					/* 将登录加密信息写入cookie */
					$this->input->set_cookie($tkcookie);
                    $this->input->set_cookie($akcookie);

                    //销毁无用数组
                    unset($tkcookie, $akcookie);
        		}
        	}
			else
			{
				$decodeurl .= '?error='.urlencode($data[1]);
			}
			//var_dump($decodeurl);exit;
			redirect($decodeurl);
        }
    }
	/* 虚拟地区比例 */
	private function areabili()
	{
		$area = array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20');
			return $area;
		$areabiliarr = array(   '01'=>array('fj'=>'55','sh'=>'15','gd'=>'10','qt'=>'20'),
			                    '02'=>array('fj'=>'53','sh'=>'12','gd'=>'15','qt'=>'20'),
							    '03'=>array('fj'=>'55','sh'=>'15','gd'=>'10','qt'=>'20'),
								'04'=>array('fj'=>'56','sh'=>'15','gd'=>'11','qt'=>'18'),
								'05'=>array('fj'=>'55','sh'=>'15','gd'=>'16','qt'=>'14'),
								'06'=>array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20'),
								'07'=>array('fj'=>'58','sh'=>'15','gd'=>'10','qt'=>'17'),
								'08'=>array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20'),
								'09'=>array('fj'=>'57','sh'=>'15','gd'=>'10','qt'=>'18'),
								'10'=>array('fj'=>'59','sh'=>'15','gd'=>'10','qt'=>'16'),
								'11'=>array('fj'=>'55','sh'=>'15','gd'=>'10','qt'=>'20'),
								'12'=>array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20'),
								'13'=>array('fj'=>'55','sh'=>'15','gd'=>'10','qt'=>'20'),
								'14'=>array('fj'=>'56','sh'=>'15','gd'=>'11','qt'=>'18'),
								'15'=>array('fj'=>'55','sh'=>'15','gd'=>'16','qt'=>'14'),
								'16'=>array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20'),
								'17'=>array('fj'=>'58','sh'=>'15','gd'=>'10','qt'=>'17'),
								'18'=>array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20'),
								'19'=>array('fj'=>'57','sh'=>'15','gd'=>'10','qt'=>'18'),
								'23'=>array('fj'=>'59','sh'=>'15','gd'=>'10','qt'=>'16'),
								'24'=>array('fj'=>'58','sh'=>'15','gd'=>'10','qt'=>'17'),
								'25'=>array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20'),
								'26'=>array('fj'=>'57','sh'=>'15','gd'=>'10','qt'=>'18'),
								'27'=>array('fj'=>'59','sh'=>'15','gd'=>'10','qt'=>'16'),
								'28'=>array('fj'=>'55','sh'=>'15','gd'=>'10','qt'=>'20'),
								'29'=>array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20'),
								'30'=>array('fj'=>'55','sh'=>'15','gd'=>'10','qt'=>'20'),
								'31'=>array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20'));
		if(is_array($areabiliarr[date('d')]) && !empty($areabiliarr[date('d')])){
			return $areabiliarr[date('d')];
		}else{
			$area = array('fj'=>'50','sh'=>'15','gd'=>'15','qt'=>'20');
			return $area;
		}
	}
}