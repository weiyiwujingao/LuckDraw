<?php
if(!defined('IN_CNFOL')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 后台活动管理页面控制器
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20 
 ****************************************************************/
class AdminActivity extends Controller
{
	/**
	 * 活动管理列表
	 * @ param string  activityName  活动名称
	 * @ param int     page          当前选择页数
	 * @ return view
	 */
    public function activityList()
    {
		//$this->checkPurview(cur_page_url(), '');

		$page = $this->loadClass('pager');

		$param = array
		(
			'activityName' => $this->get_gp('activityName')
		);

		$pageSize = ($this->get_gp('page') > 0) ? intval($this->get_gp('page')) : 1;
		
		if(empty($param['activityName']))
		{
			$param['offset'] = PAGE_NUM * ($pageSize-1);
			$param['rows']	 = PAGE_NUM;
		}
        $activit = $this->loadModel('activitymodel');
		$total   = $activit->getActivityCount($param);
		$data    = $activit->getActivityList($param);

		$linkStr = BASE_URL.'admin/activity.html?activityName='.$param['activityName'];

		$result['data']     = $data;
		$result['post']     = $param;
		$result['pageLink'] = $page->pager($total,PAGE_NUM,$linkStr);

        $this->loadView('_adminview/activity.html',$result);
    }

	/**
	 * 修改活动记录
	 * @ param string $op        操作标识符
	 * @ param int    $mid       活动ID
	 * @ param string $newName   新活动名称
	 * @ param int    $startDate 开始活动日期
	 * @ param int    $endDate   结束活动日期
	 * @ param int    $uptime    更新时间
	 * @ return json
	 */
	public function action($op='')
	{
		$activit = $this->loadModel('activitymodel');
		switch($op)
		{
			case 'add':
			case 'mod':
				$mid = $this->get_gp('mid');
				$newName = $this->get_gp('newName');
				$startDate = $this->get_gp('startDate');
				$endDate   = $this->get_gp('endDate');
				
				if(empty($newName) || empty($startDate) || empty($endDate))
					$this->returnJson('参数传递异常,请联系管理员',1);

				/* 公共变量 */
				$bindVar = array
				(
					'newName'   => trim($newName),
					'startDate' => strtotime($startDate),
					'endDate'   => strtotime($endDate),
					'upTime'    => time()
				);			
				if(is_numeric($mid) && $mid > 0)
				{
					$bindVar['mid'] = intval($mid);

					$is_succ = $activit->upActivityById($bindVar);

					if($is_succ)
						$this->returnJson('修改成功',0);
					else
						$this->returnJson('修改失败',1);
				}
				else
				{
					$is_succ = $activit->adActivity($bindVar);
					if($is_succ)
						$this->returnJson('添加成功',0);
					else
						$this->returnJson('添加失败',1);
				}	
			break;
		}
	}

	/**
	 * 附件上传,常量配置请参看config/config.php
	 * @ param string $mid  记录ID
	 * @ return json
	 */
	public function attUpload($mid=0)
	{
		if(!is_numeric($mid) || $mid < 1)
		{
			$error = '参数值不合法,请联系管理员';
			$this->returnText($error,1);
		}
		$min = intval($mid);
		/* 文件保存目录路径 */
		$savePath = ATT_PATH;
		/* 上传失败 */
		if(!empty($_FILES['fileToUpload']['error']))
		{
			switch($_FILES['fileToUpload']['error'])
			{
				case '1':
					$error = '超过最大2M允许的大小';
					break;
				case '2':
					$error = '超过表单允许的大小';
					break;
				case '3':
					$error = '文档只有部分被上传';
					break;
				case '4':
					$error = '请选择图片';
					break;
				case '6':
					$error = '找不到临时目录';
					break;
				case '7':
					$error = '写文件到硬盘出错';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = '未知错误';
			}
			$this->returnText($error,1);
		}

		/* 有上传文件时 */
		if(empty($_FILES) === false)
		{
			/* 原文件名 */
			$fileName = $_FILES['fileToUpload']['name'];
			/* 服务器上临时文件名 */
			$tmpName  = $_FILES['fileToUpload']['tmp_name'];
			/* 文件大小 */
			$fileSize = $_FILES['fileToUpload']['size'];
			/* 检查文件名 */
			if(empty($fileName))
			{
				$error = '文档名字有误,请检查';
				$this->returnText($error,1);
			}
			/* 检查目录 */
			if(@is_dir($savePath) === false)
			{
				$error = '附件目录不存在';
				$this->returnText($error,1);
			}
			/* 检查目录写权限 */
			if(@is_writable($savePath) === false)
			{
				$error = '附件目录没有写权限';
				$this->returnText($error,1);
			}
			/* 检查是否已上传 */
			if(@is_uploaded_file($tmpName) === false)
			{
				$error = '上传失败';
				$this->returnText($error,1);
			}
			/* 文件大小暂不检查 */
			if($fileSize > ATT_SIZE)
			{
				$error = '文件超过限制大小,请检查';
				$this->returnText($error,1);
			}
			/* 获得文件扩展名 */
			$tempArr = explode('.',$fileName);
			$fileExt = array_pop($tempArr);
			$fileExt = strtolower(trim($fileExt));
			/* 检查扩展名 */
			if(in_array($fileExt, array('csv')) === false)
			{
				$error = '只允许上传cvs文档,谢谢合作';
				$this->returnText($error,1);
			}
			/* 创建文件夹 */
			$savePath .= date('Ymd') . '/';

			if(file_exists($savePath) === false)
				mkdir($savePath);
			/* 新文件名 */
			$newFileName = time(). rand(1000,9999) . '.' . $fileExt;
			/* 移动文件 */
			$filePath = $savePath . $newFileName;
			if(move_uploaded_file($tmpName, $filePath) === false)
			{
				$error = '上传失败,请联系管理员';
				$this->returnText($error,1);
			}
			@chmod($filePath, 0644);
			$fileUrl = BASE_URL.'static/doc/'.date('Ymd').'/'.$newFileName;
			$activit = $this->loadModel('activitymodel');

			$bindVar = array('mid'=>$mid,'attPath'=>$fileUrl,'upTime'=>time());
			if($activit->upActivityAttById($bindVar) && $this->saveFileCache($filePath) > 0)
			{
				$error = '上传成功';
				$this->returnText($error,0);
			}
			else
			{
				$error = '上传失败';
				$this->returnText($error,1);
			}
		}
	}

	private function saveFileCache($path)
	{
		if(!file_exists($path))
		{
			$error = '上传文件遇到异常,请联系管理员';
			$this->returnText($error,1);
		}
		$cacheFile = array();
		$fileArr = file($path);
		foreach($fileArr as $key => $rs)
		{
			$row = explode(',',$rs);
			$row = array_slice($row,0,7);
			if(count($row) != 7)
			{
				unset($row,$fileArr);
				$error = 'CVS文件第'.($key+1).'行格式不正确,请检查';
				$this->returnText($error,1);
			}
			if(empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4]) || empty($row[5]) || empty($row[6]))
			{
				unset($fileArr);
				$error = 'CVS文件第'.($key+1).'行数据缺失,请检查';
				$this->returnText($error,1);
			}
			$class = trim($row[6]);
			$title = mb_convert_encoding(trim($row[0]),'utf-8','gbk');
			$cacheFile[$class][$title][] = $title;
			$cacheFile[$class][$title][] = mb_convert_encoding(trim($row[1]),'utf-8','gbk');
			$cacheFile[$class][$title][] = mb_convert_encoding(trim($row[2]),'utf-8','gbk');
			$cacheFile[$class][$title][] = mb_convert_encoding(trim($row[3]),'utf-8','gbk');
			$cacheFile[$class][$title][] = mb_convert_encoding(trim($row[4]),'utf-8','gbk');
			$cacheFile[$class][$title][] = trim($row[5]);
		}
		return file_put_contents(CACHE_PATH.'item.cache',serialize($cacheFile));
	}

	/**
	 * 输出json结构
	 * @ param string $info   返回信息
	 * @ param int    $errno  错误码
	 * @ return json
	 */
	private function returnJson($info,$errno=0)
	{
		exit(json_encode(array('errno'=>$errno,'info'=>$info)));
	}

	/**
	 * 输出文本结构
	 * @ param string $info   返回信息
	 * @ param int    $errno  错误码
	 * @ return json
	 */
	private function returnText($info,$errno=0)
	{
		header("Content-type: text/html; charset=utf-8");
		exit($info."|<a href='".BASE_URL."admin/activity.html'>返回</a>");
	}
}