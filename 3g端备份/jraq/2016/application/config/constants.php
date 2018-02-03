<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*
|--------------------------------------------------------------------------
| global constant
|--------------------------------------------------------------------------
*/
/* 定义缓存位置 */
define('CACHE_PATH', APPPATH.'cache/');
/* 线上日志路径 */
define('LOG_PATH', '/var/tmp/');
/* 授权KEY */
define('KEYS', '49b7c8876d8cb85b');
define('TIMES', time());

/* 抽奖API */
define('LUCKDRAW_API', 'http://m.3g.cnfol.com/jraq/2016');

/* 168第三方登录接口 */
define('LOGIN_168URL', 'http://www.168p2p.com/?user&q=api_blog/%s/login&key=%s&time=%s&back_url=%s');

/* 168手机登录接口 */
define('LOGIN_168URL2', 'http://www.168p2p.com/?user&q=api_blog/%s_login&key=%s&time=%s&back_url=%s');

/* 登录后回调接口 */
define('CALLBACK_168URL', 'http://m.3g.cnfol.com/jraq/2016/callback_url.html');

/* 红包接口 */
define('REGBAG_API', 'http://www.168p2p.com/api/redbag/get4vote.php');

/* End of file constants.php */
/* Location: ./application/config/constants.php */