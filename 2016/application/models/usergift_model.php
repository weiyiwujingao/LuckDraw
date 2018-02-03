<?php
/****************************************************************
 * Cnfolphp FrameWork 1.0 后台用户管理模型
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class Usergift_model extends CI_Model{

	/* pdo对象一枚 */
	//private $db = null;

     public function __construct()
	{
		parent::__construct();
		$this->load->database();
    }
	public function show($bindVar)
	{
		//$this->db->select('*');
		//$query=$this->db->get('cnfol_users');
		//$a = $query->result_array();
		
		//$bindVal   = array('aid'=>1,'gid'=>$gift_type[$giftID],'phone'=>$phone,'source'=>1,'upTime'=>time());
		$this->db->insert('cnfol_users', $bindVar);
		return $this->db->insert_id();
	}
	public function selectuser($username)
	{
		$this->db->select('id');
		//$this->db->where('username',$username);
		//$query=$this->db->get('cnfol_users');
		$query = $this->db->get_where('cnfol_users', array('username' => $username), 1, 0); 
		$a = $query->result_array();
		return $a;
		
	}
	/**
	 * 添加用户记录
	 * @ param int   $aid     活动ID
	 * @ param int   $gid     奖项ID
	 * @ param int   $phone   手机号
	 * @ param int   $source  来源
	 * @ param int   $upTime  更新时间
	 * @ return bool
	 */
    public function insertUser($bindVar=array())
    {
        $this->db->insert('cnfol_users', $bindVar);
		return $this->db->insert_id();
    }
	/**
	 * 添加用户记录
	 * @ param int   $aid     活动ID
	 * @ param int   $gid     奖项ID
	 * @ param int   $phone   手机号
	 * @ param int   $source  来源
	 * @ param int   $upTime  更新时间
	 * @ return bool
	 */
    public function updatetUser($phone,$area,$username)
    {
      /* $data = array(
               'phone' => $phone,
            );
		$this->db->where('username',$username);
		$this->db->update('cnfol_users', $data); */
		$sql = "UPDATE cnfol_users SET phone='{$phone}',area='{$area}' WHERE username ='{$username}' ORDER BY id DESC LIMIT 1";
		$this->db->query($sql);
    }
	/**
	 * 查询电话归属地
	 * @ param int   $phone		抽奖电话
	 * @ return array  query
	 */
	public function selectmobile($phone)
    {
        $query = $this->db->get_where('dm_mobile', array('MobileNumber' => $phone), 1, 0);
		$a = $query->result_array();
		return $a;
    }
	 /**
	 * 获取168红包
	 *
	 * @param integer $data['userid'] 用户id
	 * @param integer $data['giftid'] 168送礼物的帐号id
	 * @return boolean
	 */
	public function get_redbag($data)
	{
		@error_log(print_r($data,true).date('ymd').PHP_EOL,3,APPPATH.'logs/168_lottery_data.log');
		//exit(json_encode(array('flag'=>"6")));
		
		$flag = FALSE;

		$param = array('user_id'=>$data['userid'], 'ordid'=>$data['giftid']);
		
		$apiurl  = REGBAG_API . '?' . http_build_query($param);

		$response = file_get_contents($apiurl);

		/* 记录红包接口响应数据 */
		logs($apiurl . PHP_EOL . $response, 'get_redbag');

		$response = json_decode($response, TRUE);

		if(!empty($response))
		{
			/* 记录红包接口响应数据 */
			logs(print_r($response, TRUE), 'get_redbag');

			/* 成功error为0,其他均为失败 */
			if($response['error'] == '0') $flag = TRUE;
		}
		@error_log(print_r($flag,true).'-----'.date('ymd').PHP_EOL,3,APPPATH.'logs/168_lottery_data.log');
		return $flag;
	}

}