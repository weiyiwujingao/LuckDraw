<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/****************************************************************
 * 公共函数
 *---------------------------------------------------------------
 * Copyright (c) 2004-2015 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $author:linfeng $addtime:2015-09-03
 ****************************************************************/

/**
  * 输出友好的调试信息
  *
  * @param mixed $vars 需要判断的日期
  * @return mixed
  */
function t($vars)
{
	if(is_array($vars))
		exit("<pre><br>" . print_r($vars, TRUE) . "<br></pre>".rand(1000,9999));
	else
		exit($vars);
}

/**
  * 处理缓存键名称
  *
  * @return string
  */
function get_keys()
{
    $argList = func_get_args();

	return join('_', $argList);
}

/**
  * 返回json结构,并支持ajax跨域
  *
  * @param array  $data 数组
  * @param string $call 匿名函数
  * @return json
  */
function returnJson($data = array(), $call = '')
{ 
	exit(empty($call) ? json_encode($data) : $call.'('.json_encode($data).')');
}

/**
  * cur_page_url 获取当前地址
  *
  * @return string
  */
function get_url()
{
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self     = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$path_info    = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	$relate_url   = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}
/** 
  * 安全过滤函数1,转义单引号
  * 
  * @param  mixed   $string 字符串/数组 
  * @param  integer $force  强制进行过滤
  * @param  boolean $strip  是否需要去除反转义符号
  * @return mixed 
  */  
function filter_slashes($string, $force = 1, $strip = FALSE)
{
	/* 如果是表单则需要判断MAGIC_QUOTES_GPC状态 */
	if(!MAGIC_QUOTES_GPC_ON || $force)
	{
		if(is_array($string))
		{
			foreach($string as $key => $val)
			{
				$string[$key] = filter_slashes($val, $force, $strip);
			}
		}
		else
		{
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	$string = filter_sql($string);
	$string = filter_str($string);
	$string = filter_html($string);

	return $string;
}

/** 
  * 安全过滤函数2,过滤html、进制代码
  * 
  * @param  mixed $string 需要过滤的数据 
  * @param  mixed $flags  是否使用PHP自带函数
  * @return mixed 
  */  
function filter_html($string, $flags = NULL)
{
	if(is_array($string))
	{
		foreach($string as $key => $val)
			$string[$key] = filter_html($val, $flags);
	}
	else
	{
		if($flags === NULL)
		{
			$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
			if(strpos($string, '&amp;#') !== FALSE)
				$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
		}
		else
		{
			if(PHP_VERSION < '5.4.0')
				$string = htmlspecialchars($string, $flags);
			else
				$string = htmlspecialchars($string, $flags, 'UTF-8');
		}
	}
	return $string;
}

/**
  * 安全过滤函数3,数据加下划线防止SQL注入
  *
  * @param  string $value 需要过滤的值
  * @return string
  */
function filter_sql($value)
{
	$sql = array("select", 'insert', "update", "delete", "\'", "\/\*", 
					"\.\.\/", "\.\/", "union", "into", "load_file", "outfile");
	$sql_re = array("","","","","","","","","","","","");

	return str_replace($sql, $sql_re, $value);
}

/**
  * 安全过滤函数4,过滤特殊有危害字符
  * 
  * @param string $value 需要过滤的数据
  * @return string
  */
function filter_str($value)
{
	$value = str_replace(array("\0","%00","\r"), '', $value); 
	$value = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $value);
	$value = str_replace(array("%3C",'<'), '&lt;', $value);
	$value = str_replace(array("%3E",'>'), '&gt;', $value);
	$value = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $value);

	return $value;
}

/**
  * 关联数据排序
  *
  * @param array   $array     排序数组
  * @param string  $keys      排序字段 
  * @param integer $ordertype 排序方式 0:倒序 1:升序
  * @param boolean $flag      是否保留原key
  * @return array
  */
function array_sort($array, $keys = 'DiffPriceRate', $ordertype = 0, $flag = FALSE)
{ 
	$keysvalue = $newvalue = array();

	foreach($array as $key => $rs)
	{
		$keysvalue[$key] = $rs[$keys];
	}

	$ordertype ? asort($keysvalue) : arsort($keysvalue);

	reset($keysvalue);

	foreach($keysvalue as $key => $rs)
	{
		$flag ? $newvalue[] = $array[$key] : $newvalue[$key] = $array[$key];
	}
	return $newvalue; 
}

/**
  * utf-8字符串截取
  *
  * @param string  $datastr 要截取的字符串
  * @param integer $width   要求长度
  * @param boolean $point   是否添加缩略字符
  * @return string
  */
function utf8_cutstr($datastr, $width = 20, $point = FALSE)
{
    $start    = 0;
    $encoding = 'UTF-8';
	$datastr  = trim($datastr);
    
	$trimmarker = $point ? '...' : '';
    
    if($width == '')
        $width = mb_strwidth($str, $encoding);

    return htmlspecialchars(mb_strimwidth($datastr, $start, $width, $trimmarker, $encoding));
}

/**
 * 记录和统计时间(微秒)和内存使用情况
 * 使用方法:
 * <code>
 * 记录开始标记位 runTime('begin');
 * ... 区间运行代码
 * 记录结束标签位runTime('end');
 * 统计区间运行时间 精确到小数后6位 echo runTime('begin','end',6);
 * 统计区间内存使用情况echo runTime('begin','end','m');
 * 如果end标记位没有定义,则会自动以当前作为标记位
 * 其中统计内存使用需要 MEMORY_LIMIT_ON 常量为true才有效
 * </code>
 * @param string $start 开始标签
 * @param string $end 结束标签
 * @param integer|string $dec 小数位或者m
 * @return mixed
 *
 */
function runTime($start, $end = '', $dec = 4)
{
    static $_mem  = array();
    static $_info = array();

    if(is_float($end))
	{ 
		/* 记录时间 */
        $_info[$start] = $end;
    }
	else if(!empty($end))
	{ 
		/* 统计时间和内存使用 */
        if(!isset($_info[$end]))
			$_info[$end] = microtime(TRUE);

        if(MEMORY_LIMIT_ON && $dec=='m')
		{
            if(!isset($_mem[$end])) $_mem[$end] = memory_get_usage();
				
            return number_format(($_mem[$end] - $_mem[$start])/1024);
        }
		else
		{
            return number_format(($_info[$end] - $_info[$start]),$dec);
        }
    }
	else
	{	/* 记录时间和内存使用 */
        $_info[$start] = microtime(TRUE);

        if(MEMORY_LIMIT_ON) $_mem[$start] = memory_get_usage();
    }
    return NULL;
}

/**
  * 日志记录
  *
  * @param string $msg  内容
  * @param string $file 日志文件名
  * @return void
  */
function logs($msg, $file = 'system')
{
	$log = '['.date('H:i:s').']['.$msg.']'.PHP_EOL;

	$filePath = APPPATH . 'logs/' . $file . '_' . date('Ymd') . '.log';

	error_log($log, 3, $filePath);
}

/**
  * 加密程序
  * 
  * @param string  $string    明文/密文
  * @param string  $operation 'DECODE'表示解密,其它表示加密
  * @param string  $key       密匙
  * @param integer $expiry    密文有效期
  * @return string
  */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
	// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
	$ckey_length = 4;
	// 密匙
	$key = md5($key ? $key : "sdf168dfsfsdfd");//
	// 密匙a会参与加解密
	$keya = md5(substr($key, 0, 16));
	// 密匙b会用来做数据完整性验证
	$keyb = md5(substr($key, 16, 16));
	// 密匙c用于变化生成的密文
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
	// 参与运算的密匙
	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);
	// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
	// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();

	// 产生密匙簿
	for ($i = 0; $i <= 255; $i++)
	{
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
	for($j = $i = 0; $i < 256; $i++)
	{
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	// 核心加解密部分
	for($a = $j = $i = 0; $i < $string_length; $i++)
	{
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		// 从密匙簿得出密匙进行异或，再转成字符
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE')
	{
		// substr($result, 0, 10) == 0 验证数据有效性
		// substr($result, 0, 10) - time() > 0 验证数据有效性
		// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
		// 验证数据有效性，请看未加密明文的格式
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16))
		{
			return substr($result, 26);
		}
		else
		{
			return '';
		}
	}
	else
	{
		// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
		// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
		return $keyc . str_replace('=', '', base64_encode($result));
	}
}

/**
 * 数据格式化
 *
 * @param integer/float $number 需要格式化的数字
 * @param integer       $count  保留小数点位数
 * @return integer/float
 *
 */
function nf($number, $count=2)
{
    return number_format($number, $count, '.', '');
}
/**
 * 虚拟数据格式
 *
 * @param integer/float $number 需要格式化的数字
 * @param integer       $count  保留小数点位数
 * @return integer/float
 *
 */
function str_repace($str)
{
	$str2 = substr($str,3,-4);
	$tr = '';
	for($i=0;$i<strlen($str2);$i++)
	{
		$tr .= '*';
	}
	$st = str_replace($str2,$tr,$str);
	return $st;
}	

function curl_get($url,$phone)
{
	
	@error_log(print_r($phone,true).PHP_EOL,3,APPPATH.'logs/168_lottery_data.log');
	//exit(json_encode(array('flag'=>"5")));
	 $ch = curl_init();  
    // 2. 设置选项，包括URL  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 0);
    // 3. 执行并获取HTML文档内容  
    $info = curl_exec($ch); 
	$info = json_decode($info,TRUE);
    // 4. 释放curl句柄  
    curl_close($ch); 
	/* 跟踪日志 */
	if ($info['flag'] != 0)
		@error_log(date('H:i:s').PHP_EOL.print_r($phone.'-----------'.$info['info'],true).PHP_EOL.'-----------'.PHP_EOL,3,APPPATH.'logs/168_lottery_data_'.date('Ymd').'.log');
	@error_log(date('H:i:s').PHP_EOL.print_r($phone.'-----------'.$info['info'],true).PHP_EOL.'-----------'.PHP_EOL,3,APPPATH.'logs/168_lottery_data_'.date('Ymd').'.log');
	//return $info['flag'];
}

/* End of file func_helper.php */
/* Location: ./application/helper/func_helper.php */