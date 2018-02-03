<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/****************************************************************
 * Cnfolphp FrameWork 1.0 奖项配置文件
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-7-8 $Utime:2014-10-13
 ****************************************************************/
$config = array();
/*
$config['gift'] = array
(
	'pc' => array
	(
		'1' => array('giftname'=>'10元话费','count'=>3),
		'2' => array('giftname'=>'25元红包','count'=>2),
		'3' => array('giftname'=>'未中奖','count'=>3),
		'5' => array('giftname'=>'200金币','count'=>1),
		'6' => array('giftname'=>'50元红包','count'=>1)
	),
	'3g' => array
	(
		'1' => array('giftname'=>'10元话费','count'=>3),
		'2' => array('giftname'=>'25元红包','count'=>2),
		'3' => array('giftname'=>'未中奖','count'=>3),
		'5' => array('giftname'=>'200金币','count'=>1),
		'6' => array('giftname'=>'50元红包','count'=>1)
	)
);
*/
$config['gift'] = array
(
	'pc' => array
	(
	
		'2' => array('giftname'=>'25元红包','count'=>500,'giftid' => '51547'),
		'4' => array('giftname'=>'10元话费','count'=>5,'giftid' => ''),
		'5' => array('giftname'=>'精美相框','count'=>5,'giftid' => ''),
		'6' => array('giftname'=>'100元红包','count'=>1,'giftid' => '51546'),
		'8' => array('giftname'=>'50元红包','count'=>6,'giftid' => '53637')
	),
	'3g' => array
	(
		'2' => array('giftname'=>'25元红包','count'=>500,'giftid' => '51547'),
		'4' => array('giftname'=>'10元话费','count'=>5,'giftid' => ''),
		'5' => array('giftname'=>'精美相框','count'=>5,'giftid' => ''),
		'6' => array('giftname'=>'100元红包','count'=>1,'giftid' => '51546'),
		'8' => array('giftname'=>'50元红包','count'=>6,'giftid' => '53637')
	)
);