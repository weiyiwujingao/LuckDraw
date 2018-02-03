<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/****************************************************************
 * 行情系统-扩展CI Controller类
 *---------------------------------------------------------------
 * Copyright (c) 2004-2015 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $author:linfeng $addtime:2014-9-30
 ****************************************************************/

class MY_Controller extends CI_Controller
{
    public function __construct()
	{
        parent::__construct();
    }

   /**
	 * 生成通讯密钥
	 *
	 * @param string $onlyid 唯一的标识
	 * @return array
	 */
	protected function get_secret_key($onlyid = '')
	{
		$time = time();

		$keys = md5($time . config_item('encryption_key') . $onlyid);

		return array('time' => $time, 'keys1' => substr($keys, 0, 16), 'keys2' => substr($keys, 17, 32));
	}

   /**
	 * 验证通讯密钥
	 *
	 * @param string  $key     要验证的密钥
	 * @param string  $key1    cookie中的key
	 * @param integer $time    生成密钥时使用的时间戳
	 * @param string  $only_id 唯一的标识
	 * @return array
	 */
	protected function check_secret_key($key1, $key2, $time, $onlyid = '')
	{
		/* 判断密钥是否已过期 */
		if(((time() - $time) > 3600) || (!$key1 && !$key2))
			return FALSE;

		$keys = md5($time . config_item('encryption_key') . $onlyid);
		
		if($key1 && (substr($keys, 0, 16) != $key1))
			return FALSE;

		if($key2 && substr($keys, 17, 32) != $key2)
			return FALSE;
		
		return TRUE;
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */