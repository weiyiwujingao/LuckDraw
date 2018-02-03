<?php
/****************************************************************
 * Cnfolphp FrameWork 1.0 后台用户管理模型
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class Js_model extends CI_Model{

	function getTagData($tag,$record=10,$charset='')
	{
		global $md5key,$len,$debug;
		$mem = new Memcache;
		$mem->AddServer('memcache.cache.cnfol.com',11211);
		$mem->AddServer('memcache2.cache.cnfol.com',11211);
		$mem->AddServer('memcache3.cache.cnfol.com',11211);
		$mem->AddServer('memcache4.cache.cnfol.com',11211);
		//取不到 memcache 再查数据库
		$data = $mem->get($md5key);
		//$data = null;
		if ($debug || !$data) {
			$tag = iconv('gb2312','utf-8',$tag);
			$link_new = mysql_connect('172.20.1.154','CmsNew','c8wjcila5!');
			mysql_select_db('cnfolCMS',$link_new);
			mysql_query("set names utf8",$link_new);
			$rs = mysql_query("SELECT TagId FROM cnfol_tag WHERE Name = '$tag' limit 1",$link_new);
			if (mysql_num_rows($rs) < 1) {
				echo 'error=1&tag='.$tag.'&msg=该标签不存在';
				mysql_close($link_new);
				exit;
			}
			$data = array();
			$C = array(); //articleclass:ChannelID
			$D = array(); //fol_admin.syschannel:Domain
			$TimeSort1 = array();
			$TimeSort2 = array();
			$tagid = mysql_result($rs, 0);
			if($debug) echo $tagid;
			$where = 1;
			$sql = "SELECT DISTINCT ContId as aid,ContTime as atime FROM cnfol_tagcontent WHERE TagId='$tagid' ORDER BY aid DESC limit $record";
			if ($debug) echo $sql;
			$rs2 = mysql_query($sql,$link_new);
			while ($array2 = mysql_fetch_array($rs2, MYSQL_ASSOC)) {

				if($array2['atime']){
					$table = getTable($array2['atime']);
					$ars = mysql_query("SELECT CatId,Title,CreatedTime,Url FROM $table WHERE ContId='{$array2['aid']}'",$link_new);
					if(!$ars)  continue;
					$arow = mysql_fetch_array($ars, MYSQL_ASSOC);
				}else{
					$ars = mysql_query("SELECT CatId,Title,CreatedTime,Url FROM cnfol_content WHERE ContId='{$array2['aid']}'",$link_new);
					if(!$ars)  continue;
					unset($arow);
					$arow = mysql_fetch_array($ars, MYSQL_ASSOC);
				}
				
				//判断没有归档数据再次提取
				if(!$arow['Title']){
					$ars = mysql_query("SELECT CatId,Title,CreatedTime,Url FROM cnfol_content WHERE ContId='{$array2['aid']}'",$link_new);
					if(!$ars)  continue;
					unset($arow);
					$arow = mysql_fetch_array($ars, MYSQL_ASSOC);
				}
				if($debug) var_dump($arow);
					if($arow){
						$array2['ClassID'] = filtSql($arow['CatId']);
						if($len){
							$array2['Title'] = convert(cnsubstr($arow['Title'],$len),$charset);
						}else{
							$array2['Title'] = convert($arow['Title'],$charset);
						}
						$array2['CreatedTime'] = filtSql($arow['CreatedTime']);
						$array2['Url'] = filtSql($arow['Url']);
						//取得需要的数据
						$data[] = array('url'=>$array2['Url'],'title'=> $array2['Title'],'date'=> $array2['CreatedTime']);
				   }
				
			}
			if ($debug) print_r($data);

			//Cache 10 分钟
			$data = serialize($data);
			$mem->set($md5key, $data, false, 60*10);
			mysql_close($link_new);
			return $data;
		}else{
			return $data;
		}

	}

}