<?php
if(!defined('IN_CNFOL')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 后台活动管理模型
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class ActivityModel
{
	private $db = null;

    function __construct($db)
	{
		$this->db = $db;
    }

	/**
	 * 活动列表
	 * @ param string $data['activityname'] 活动名称
	 * @ param array  $bindVar 绑定字段变量
	 * @ return array
	 */
    public function getActivityList($data,$bindVar=array())
    {
		if(isset($data['activityName']) && !empty($data['activityName']))
		{
			$where[] = ' a.activityname = ?';
			$bindVar[] = trim($data['activityName']);
		}

		if(!empty($where))
		{
            $where = ' where ' . join(' and ', $where);
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
            $order = ' order by a.' . $data['order'] . ' desc';
        }
		else
		{
            $order = ' order by a.uptime desc ';
        }

       $sql = 'select a.id,a.startdate,a.enddate,a.activityname,a.attpath,a.uptime from cnfol_activity a'.$where.$order.$limit;

		$query = $this->db->prepare($sql);

        $query->execute($bindVar);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

	/**
	 * 活动列表
	 * @ param string $data['activityname'] 活动名称
	 * @ param array  $bindVar 绑定字段变量
	 * @ return array
	 */
    public function getActivityCount($data,$bindVar=array())
    {
		if(isset($data['activityName']) && !empty($data['activityName']))
		{
			$where[] = ' a.activityname = ?';
			$bindVar[] = trim($data['activityName']);
		}

		if(!empty($where))
		{
            $where = ' where ' . join(' and ', $where);
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
        $sql = 'select count(*) from cnfol_activity a'.$where;

		$query = $this->db->prepare($sql);

        $query->execute($bindVar);

        return $query->fetchColumn();
    }

	/**
	 * 修改活动记录
	 * @ param int    $mid       活动ID
	 * @ param string $newName   新活动名称
	 * @ param int    $startDate 开始活动日期
	 * @ param int    $endDate   结束活动日期
	 * @ param int    $uptime    更新时间
	 * @ return bool
	 */
    public function upActivityById($bindVar=array())
    {
        $sql = 'update cnfol_activity set activityname=:newName,startdate=:startDate,enddate=:endDate,uptime=:upTime where id=:mid';

		$query = $this->db->prepare($sql);

        return $query->execute($bindVar);
    }

	/**
	 * 添加活动记录
	 * @ param string $newName   活动名称
	 * @ param int    $startDate 开始活动日期
	 * @ param int    $endDate   结束活动日期
	 * @ param int    $uptime    更新时间
	 * @ return bool
	 */
    public function adActivity($bindVar=array())
    {
        $sql = 'insert into cnfol_activity(activityname,startdate,enddate,uptime)values(:newName,:startDate,:endDate,:upTime)';

		$query = $this->db->prepare($sql);

        return $query->execute($bindVar);
    }

	/**
	 * 更新题库
	 * @ param int    $mid  活动ID
	 * @ return bool
	 */
    public function upActivityAttById($bindVar=array())
    {
        $sql = 'update cnfol_activity set attpath=:attPath,uptime=:upTime where id=:mid';

		$query = $this->db->prepare($sql);

        return $query->execute($bindVar);
    }
}