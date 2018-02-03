<?php
if(!defined('IN_CNFOL')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 后台用户管理页面控制器
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class AdminUser extends Controller
{
	/**
	 * 用户管理列表
	 * @ param int    aid   活动ID
	 * @ param int    gid   奖项ID
	 * @ param string phone 手机号
	 * @ return view
	 */
    public function userList()
    {
		//$this->checkPurview(cur_page_url(), '');

		$page = $this->loadClass('pager');

		$param = array
		(
			'aid' => $this->get_gp('aid'),
			'gid' => $this->get_gp('gid'),
			'phone' => $this->get_gp('phone')
		);

		$pageSize = ($this->get_gp('page') > 0) ? intval($this->get_gp('page')) : 1;	

		if(empty($param['aid']) && empty($param['gid']) && empty($param['phone']))
		{
			$param['offset'] = PAGE_NUM * ($pageSize-1);
			$param['rows']	 = PAGE_NUM;
		}
        $user  = $this->loadModel('usermodel');
		$total = $user->getUserCount($param);
		$data  = $user->getUserList($param);

		$linkStr = BASE_URL.'admin/user.html?aid='.$param['aid'].'&gid='.$param['aid'].'&phone='.$param['phone'];

		$result['data']     = $data;
		$result['post']     = $param;
		$result['grade']    = $user->getGradeList();
		$result['activit']  = $user->getActList();
		$result['pageLink'] = $page->pager($total,PAGE_NUM,$linkStr);

        $this->loadView('_adminview/userlist.html',$result);
    }
}