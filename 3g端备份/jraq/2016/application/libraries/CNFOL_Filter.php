<?php
if(! defined('BASEPATH')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 安全过滤类
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class CNFOL_Filter
{
	/**
	 * 获取GET或者POST的参数值,经过过滤
	 * 1.如果不指定$type类型,则获取同名的,POST优先
	 * 2.$isfilter 默认开启,强制转换请求的数据
	 * 3.该方法在Controller层中,获取所有GET或者POST数据,都需要走这个接口
	 * @param  string|array $value 参数
	 * @param  string|array $type  获取GET或者POST参数,P - POST ， G - GET, U - PUT , D -DEL
	 * @param  bool         $isfilter 变量是否过滤
	 * @return string|array
	 */
	public function get_gp($value, $type = null, $isfilter = false)
	{
		if($type == 'U' || $type == 'D')
		{
			parse_str(file_get_contents('php://input'), $requestData);
		}
		$temp = '';
		if(!is_array($value))
		{
			if($type === null)
			{
				if(isset($_GET[$value])) $temp = $_GET[$value];
				if(isset($_POST[$value])) $temp = $_POST[$value];
			}
			elseif ($type == 'U' || $type == 'D')
			{ //PUT 和 DEL
				$temp = $requestData[$value];
			}
			else
			{
				$temp = (strtoupper($type) == 'G') ? $_GET[$value] : $_POST[$value];
			}
			$temp = ($isfilter === true) ? $this->filter_escape($temp) : $temp;
			return $temp;
		}
		else
		{
			$temp = array();
			foreach($value as $val)
			{
				if($type === null)
				{
					if (isset($_GET[$val])) $temp[$val] = $_GET[$val];
					if (isset($_POST[$val])) $temp[$val] = $_POST[$val];
				}
				elseif($type == 'U' || $type == 'D')
				{
					$temp[$val] = $requestData[$val];
				}
				else
				{
					$temp[$val] = (strtoupper($type) == 'G') ? $_GET[$val] : $_POST[$val];
				}
				$temp[$val] = ($isfilter === true) ? $this->filter_escape($temp[$val]) : $temp[$val];
			}
			return $temp;
		}
	}
	
	/**
	 * 全局变量过滤
	 * 在Controller初始化的时候已经运行过该变量,对全局变量进行处理
	 * @ return void 
	 */
	public function filter()
	{
		if(is_array($_SERVER))
		{
			foreach($_SERVER as $k => $v)
			{
				if(isset($_SERVER[$k]))
				{
					$_SERVER[$k] = str_replace(array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e'), '', $v);
				}
			}
		}
		unset($_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);
		self::filter_slashes($_GET);
		self::filter_slashes($_POST);
		self::filter_slashes($_COOKIE);
		self::filter_slashes($_FILES);
		self::filter_slashes($_REQUEST);
	}
	
	/**
	 * 加反斜杠，防止SQL注入
	 * @param  string $value 需要过滤的值
	 * @return string
	 */
	public static function filter_slashes(&$value)
	{
		//开启魔术变量
		if(get_magic_quotes_gpc()) return false;
		$value = (array) $value;
		foreach($value as $key => $val)
		{
			if(is_array($val))
			{
				self::filter_slashes($value[$key]);
			}
			else
			{
				$value[$key] = addslashes($val);
			}
		}
	}
	
	/**
	 * 过滤javascript,css,iframes,object等不安全参数 过滤级别高
	 * @param  string $value 需要过滤的值
	 * @return string
	 */
	public function filter_script($value)
	{
		$value = preg_replace("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i","&111n\\2",$value);
		$value = preg_replace("/<script(.*?)>(.*?)<\/script>/si","",$value);
		$value = preg_replace("/<iframe(.*?)>(.*?)<\/iframe>/si","",$value);
		$value = preg_replace("/<object.+<\/object>/iesU",'',$value);
		return $value;
	}
	
	/**
	 * 过滤HTML标签
	 * @param  string $value 需要过滤的值
	 * @return string
	 */
	public function filter_html($value)
	{
		if(function_exists('htmlspecialchars')) return htmlspecialchars($value);
		return str_replace(array("&", '"', "'", "<", ">"), array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;"), $value);
	}
	
	/**
	 * 对进入的数据加下划线 防止SQL注入
	 * @param  string $value 需要过滤的值
	 * @return string
	 */
	public function filter_sql($value)
	{
		$sql = array("select", 'insert', "update", "delete", "\'", "\/\*", 
						"\.\.\/", "\.\/", "union", "into", "load_file", "outfile");
		$sql_re = array("","","","","","","","","","","","");
		return str_replace($sql, $sql_re, $value);
	}
	
	/**
	 * 通用数据过滤
	 * @param string $value 需要过滤的变量
	 * @return string|array
	 */
	public function filter_escape($value)
	{
		if (is_array($value))
		{
			foreach ($value as $k => $v)
			{
				$value[$k] = self::filter_str($v);
			}
		}
		else
		{
			$value = self::filter_str($value);
		}
		return $value;
	}
	
	/**
	 * 字符串过滤,过滤特殊有危害字符
	 * @param  string $value 需要过滤的值
	 * @return string
	 */
	public function filter_str($value)
	{
		$value = str_replace(array("\0","%00","\r"), '', $value); 
		$value = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $value);
		$value = str_replace(array("%3C",'<'), '&lt;', $value);
		$value = str_replace(array("%3E",'>'), '&gt;', $value);
		$value = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $value);
		return $value;
	}
	
	/**
	 * 私有路径安全转化
	 * @param string $fileName
	 * @return string
	 */
	public function filter_dir($fileName)
	{
		$tmpname = strtolower($fileName);
		$temp = array('://',"\0", "..");
		if(str_replace($temp, '', $tmpname) !== $tmpname)
		{
			return false;
		}
		return $fileName;
	}
	
	/**
	 * 过滤目录
	 * @param string $path
	 * @return array
	 */
	public function filter_path($path)
	{
		$path = str_replace(array("'",'#','=','`','$','%','&',';'), '', $path);
		return rtrim(preg_replace('/(\/){2,}|(\\\){1,}/', '/', $path), '/');
	}
	
	/**
	 * 过滤PHP标签
	 * @param string $string
	 * @return string
	 */
	public function filter_phptag($string)
	{
		return str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $string);
	}
	
	/**
	 * 返回函数
	 * @param  string $value 需要过滤的值
	 * @return string
	 */
	public function str_out($value)
	{
		$badstr = array("&", '"', "'", "<", ">", "%3C", "%3E");
		$newstr = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;", "&lt;", "&gt;");
		$value  = str_replace($newstr, $badstr, $value);
		return stripslashes($value); //下划线
	}
}