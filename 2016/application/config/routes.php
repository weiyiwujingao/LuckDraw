<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "indexhtml";
$route['404_override'] = '';

/* 活动介绍 */
$route['hdjs\.html'] = '/indexhtml/hdjs';
/* 有奖问答 */
$route['yjwd\.html'] = '/indexhtml/yjwd';
/* 抽奖页面 */
$route['cj\.html'] = '/indexhtml/cj';
/* 问卷调查 */
$route['wjdc\.html'] = '/indexhtml/wjdc';
/* 问卷调查兼容更改 */
$route['wjdciframe\.html'] = '/indexhtml/wjdcIframe';
/* 知识馆 */
$route['zsg\.html'] = '/indexhtml/zsg';
/* 获奖名单 */
$route['hjmd\.html'] = '/indexhtml/hjmd';
/* 获取中奖礼物 */
$route['gift\.html'] = '/indexhtml/getGiftID';
/* 获取中奖礼物 */
$route['newgifts\.html'] = '/indexhtml/getnewgift';
/* 电台活动 */
$route['dthd\.html'] = '/indexhtml/dthd';
/* 微博分享 */
$route['ernie\.html'] = '/indexhtml/setErnieCount';
/* 回调函数地址 */
$route['callback_url\.html'] = '/indexhtml/callback_url';
/* 抽中话费保存号码 */
$route['phone\.html'] = '/indexhtml/getPhone';
/* 抽中话费保存号码 */
$route['zsgapi\.html'] = '/indexhtml/zsgApi';

$route['test\.html'] = '/indexhtml/test';
/* 获奖名单接口 */
$route['api/windlists\.html'] = '/indexhtml/getWindLists';
/* 获奖页名单接口 */
$route['wind\.html'] = '/indexhtml/getWindList';

/* 后台奖品列表及用户列表备份 */
$route['shell/initgift\.html'] = '_shell/giftshell/initGift';
/* 后台虚拟用户设置 */
$route['shell/setVirtualUser\.html'] = '_shell/giftshell/setVirtualUser';
$route['shell/test\.html'] = '_shell/giftshell/test';

/* 电话归属地测试 */
$route['iphonearea\.html'] = '/indexhtml/phonearea';

/* End of file routes.php */
/* Location: ./application/config/routes.php */