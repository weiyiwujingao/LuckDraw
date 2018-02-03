<?php
/****************************************************************
 * Cnfolphp FrameWork 1.0 后台用户管理模型
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class User_model extends CI_Model{

	/* pdo对象一枚 */
	private $db = null;

    function __construct()
	{
		parent::__construct();
	  //  $this->expire = 600;
		//$this->load->database();
    }

	/**
	 * 用户列表
	 * @ param int $data['aid']    活动ID
	 * @ param int $data['phone']  手机号码
	 * @ param int $data['gid']    奖项等级
	 * @ param array $bindVar      绑定字段变量
	 * @ return array
	 */
    public function getUserList($data,$bindVar=array())
    {	
		$where[] = '  a.id!=4 ';		
		if(isset($data['aid']) && !empty($data['aid']))
		{
			$where[] = ' u.aid = ?';
			$bindVar[] = $data['aid'];
		}

		if(isset($data['phone']) && !empty($data['phone']))
		{
			$where[] = " u.phone = ?";
			$bindVar[] = trim($data['phone']);
		}

		if(isset($data['gid']) && !empty($data['gid']))
		{
			$where[] = ' u.gid = ?';
			$bindVar[] = $data['gid'];
		}

		if(!empty($where))
		{
            $where = ' and ' . join(' and ', $where);
        } 
		else
		{
            $where = '';
        }

        if(isset($data['offset']) && !empty($data['rows']))
		{
            $limit = ' limit ' . $data['offset'] . ',' . $data['rows'];
        }
		else
		{
			$limit = '';
		}

        if(isset($data['order']) && !empty($data['order']))
		{
            $order = ' order by u.' . $data['order'] . ' desc';
        }
		else
		{
            $order = ' order by u.uptime desc ';
        }

        $sql = 'select u.aid,u.gid,u.source,g.gradename,a.activityname,u.phone,u.uptime from cnfol_grade g,cnfol_activity a,cnfol_users u where g.id=u.gid and a.id=u.aid'.$where.$order.$limit;
#error_log($sql.PHP_EOL,3,'/var/tmp/sql.sql');
		$query = $this->db->prepare($sql);

        $query->execute($bindVar);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

	/**
	 * 获取用户总记录
	 * @ param int $data['aid']   活动ID
	 * @ param int $data['phone'] 手机号码
	 * @ param int $data['gid']   奖项等级
	 * @ param array $bindVar     绑定字段变量
	 * @ return int
	 */
    public function getUserCount($data,$bindVar=array())
    {
		
		$where[] = ' a.id != 4 ';
		if(isset($data['aid']) && !empty($data['aid']))
		{
			$where[] = ' u.aid = ?';
			$bindVar[] = $data['aid'];
		}

		if(isset($data['phone']) && !empty($data['phone']))
		{
			$where[] = " u.phone = ?";
			$bindVar[] = trim($data['phone']);
		}

		if(isset($data['gid']) && !empty($data['gid']))
		{
			$where[] = ' u.gid = ?';
			$bindVar[] = $data['gid'];
		}

		if(!empty($where))
		{
            $where = ' and ' . join(' and ', $where);
        } 
		else
		{
            $where = '';
        }

        $sql = 'select count(*) from cnfol_grade g,cnfol_activity a,cnfol_users u where g.id=u.gid and a.id=u.aid'.$where;

		$query = $this->db->prepare($sql);

        $query->execute($bindVar);

        return $query->fetchColumn();
    }

	/**
	 * select控件活动列表
	 * @ return array
	 */
    public function getActList()
    {	
		$where = " where a.id !=4 and a.id !=5 ";
        $sql = 'select a.id,a.activityname from cnfol_activity a '.$where.' limit 100';

		$query = $this->db->prepare($sql);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

	/**
	 * select控件奖项列表
	 * @ return array
	 */
    public function getGradeList()
    {	
		$where = ' where g.id not in(1,2,3) ';
        $sql = 'select g.id,g.gradename from cnfol_grade g '.$where.'limit 100';

		$query = $this->db->prepare($sql);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
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
        $sql = 'insert into cnfol_users(aid,gid,phone,source,uptime)values(:aid,:gid,:phone,:source,:upTime)';

		$query = $this->db->query($sql);

        return $this->db->affected_rows();
		//return $query->execute($bindVar);
    }
}