<?php
if(!defined('IN_CNFOL')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 后台介绍页面控制器
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class AdminDefault extends Controller
{
	/**
	 * 后台欢迎页面
	 * @ return view
	 */
    public function index()
    {
		//$param = array('type' => 4,'node' => '知识问答库后台-欢迎首页');
		//$this->checkPurview(cur_page_url(), '');
        $this->loadView('_adminview/index.html');
    }
}