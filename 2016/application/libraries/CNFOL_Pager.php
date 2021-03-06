<?php
if(! defined('BASEPATH')) exit('No direct script access allowed');   
/****************************************************************
 * Cnfolphp FrameWork 1.0 扩展类库-分页类
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class CNFOL_Pager
{ 
	/* 分页中显示多少个 */
	private $show_num = 5;
	/* 链接控制 */
	public $conf = array
	(
		'first_last'=> 1, /* 首页-尾页      0:关闭 1:开启 */
		'back_next' => 1, /* 上一页-下一页  0:关闭 1:开启 */
		'total_num' => 1, /* 是否显示总页数 0:关闭 1:开启 */
		'page_num'  => 1, /* 翻页数			0:关闭 1:开启 */
		'select'    => 1  /* 下拉列表选择	0:关闭 1:开启 */
	);
	/**
	 *	分页-分页入口
	 * 	@param int     $count   总共多少数据
	 * 	@param int     $prepage 每页显示多少条 
	 * 	@param string  $url     URL
	 *  @return string
	 */
	public function pager($count, $prepage, $url)
	{
		$count    = (int) $count;
		$prepage  = (int) $prepage;
		/* 总页数 */
		$page_num = ceil($count / $prepage);
		$page     = isset($_GET['page']) ? (int) $_GET['page'] : 1;
		$page     = ($page > $page_num) ? $page_num : ($page = ($page < 1) ? 1 : $page);
		//$url      = (strpos($url, '?') === false) ? $url . '?' : $url;
		return $this->pager_html($page_num, $url, $page);
	}
	
	/**
	 *	分页-获取分页HTML显示
	 * 	@param int    $page_num 页数
	 * 	@param string $url      URL 
	 * 	@param int    $page     当前页
	 *  @return string
	 */
	private function pager_html($page_num, $url, $page)
	{
		list($start, $end) = $this->get_satrt_and_end($page, $page_num);
		list($back, $next) = $this->get_pager_next_back_html($url, $page, $page_num);
		list($first, $last) = $this->get_first_last_html($page_num, $url);

		$html = "<div class='Page'>";
		$html .= $back;
		$html .= $first;
		$html .= $this->get_pager_num_html($start, $end, $url, $page);
		$html .= $last;
		$html .= $next;
		$html .= $this->get_total_num_html($page_num);
		$html .= $this->get_select_html($page_num, $url, $page);
		$html .= '</div>';
		return $html;
	}
		
	/**
	 *	分页-获取分页数字的列表
	 * 	@param int     $start  开始数
	 * 	@param int     $end    结束数
	 * 	@param string  $url    URL地址
	 * 	@param int     $page   当前页
	 *  @return string
	 */
	private function get_pager_num_html($start, $end, $url, $page)
	{
		if($this->conf['page_num'] == 0) return ''; //是否开启
		$html = '';
		for($i=$start; $i<=$end; $i++)
		{
			if ($i == $page)
			{
				$html .= "<a>{$i}</a>";
			}
			else
			{
				$html .= "<a href='{$url}{$this->get_uri($url,$i)}' class=\"NoNm\">{$i}</a>";
			}
		}
		return $html;
	}
	
	/**
	 *	分页-分页总页数显示
	 * 	@param int  $page_num 页数
	 *  @return string
	 */
	private function get_total_num_html($page_num)
	{
		if ($this->conf['total_num'] == 0) return ''; //是否开启
		return "&nbsp;&nbsp;<i class=\"Fl\">共有 {$page_num} 页</i>";
	}
	
	/**
	 *	分页-分页首页和尾页显示
	 * 	@param int  $page_num 页数
	 * 	@param string  $url    URL地址
	 *  @return string
	 */
	private function get_first_last_html($page_num, $url)
	{
		if($this->conf['first_last'] == 0) return array('', ''); //是否开启
		$first = "<a href='{$url}{$this->get_uri($url)}' class='NoNm'>首页 </a>";
		$last  = "<a href='{$url}{$this->get_uri($url,$page_num)}' class='NoNm'> 尾页</a>";
		return array($first, $last);
	}
	
	/**
	 *	分页-获取分页上一页-下一页HTML
	 * 	@param string  $url      URL地址
	 * 	@param int     $page     当前页
	 * 	@param int     $page_num 页数
	 *  @return string
	 */
	private function get_pager_next_back_html($url, $page, $page_num)
	{
		if($this->conf['back_next'] == 0) return array('', ''); //是否开启
		$next_page = $page + 1;
		$next = "<a href='{$url}{$this->get_uri($url,$next_page)}' class='NoNm'>下一页</a>";
		if ($page == $page_num) $next = '';
		$back_page = $page - 1;
		$back = "<a href='{$url}{$this->get_uri($url,$back_page)}' class='NoNm'>上一页</a>";
		if ($page == 1) $back = '';
		return array($back, $next);
	}
	
	/**
	 *	分页-Select选择器
	 * 	@param int     $page_num 页数
	 * 	@param string  $url      URL地址
	 * 	@param int     $page     当前页
	 *  @return string
	 */
	private function get_select_html($page_num, $url, $page)
	{
		if($this->conf['select'] == 0) return '';
		$html = '&nbsp;&nbsp;跳到<select name="select" onchange="javascript:window.location.href=this.options[this.selectedIndex].value">';
		for($i=1; $i<=$page_num; $i++)
		{
            if ($page == $i)
			{
				$selected = ' selected';
			}
			else
			{
				$selected = '';
			}
            $html .= "<option value='{$url}{$this->get_uri($url,$i)}' {$selected}>{$i}</option>";
        }
		$html .= '</select>';
		return $html;
	}
	
	/**
	 *	分页-获取分页显示数字
	 * 	@param int  $page     当前页
	 * 	@param int  $page_num 页数
	 *  @return array(start, end)
	 */
	private function get_satrt_and_end($page, $page_num)
	{
		$temp = floor($this->show_num / 2);
		if($page_num < $this->show_num) return array(1, $page_num);
		if($page <= $temp)
		{
			$start = 1;
			$end = $this->show_num;
		}
		elseif(($page_num - $temp) < $page)
		{
			$start = $page_num - $this->show_num + 1;
			$end  = $page_num;
		}
		else
		{
			$start = $page - $temp;
			$end   = $page - $temp + $this->show_num - 1;
		}
		return array($start, $end);
	}

	private function get_uri($url,$value=1)
	{
		if(strpos($url,'?') === false)
			$uri = '?page='.$value;
		else
			$uri = '&page='.$value;
		return $uri;
	}
}