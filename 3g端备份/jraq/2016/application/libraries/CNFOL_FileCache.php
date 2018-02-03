<?php
/****************************************************************
 * Cnfolphp FrameWork 1.0 文件缓存类
 *---------------------------------------------------------------
 * Copyright (c) 2004-2014 CNFOL Inc. (http://www.cnfol.com)
 *---------------------------------------------------------------
 * $Author:linfeng $Dtime:2014-6-20
 ****************************************************************/
class CNFOL_FileCache
{  
    //private static $_instance = null;

	protected $configs;

    protected $_options = array
	(
        'cache_dir'  => CACHE_PATH,//"/home/httpd/webroot/linfeng/jraq.test.cnfol.com/application/cache/",
        'prefix_key' => 'cache_',
		'log_path'   => '/var/tmp/filecache.log',
        'mode'       => '2' //mode 1 为serialize model 2为保存为可执行文件
    );

    /**
     * 构造函数
     */
    public function __construct()
	{
		//获取配置信息
		//$config = &get_config();
        //设置文件前缀
        //$this->_options['mode'] = $config[$filecache]['mode'];
        //存储方式
        //$this->_options['cache_dir']  = $config[$filecache]['cache_dir'];
		//文件缓存前缀
		//$this->_options['prefix_key'] = $config[$filecache]['prefix'];
	}

    /**
     * 得到本类实例
     *
     * @return Ambiguous
     */
	/*
    public static function getInstance()
    {
        if(self::$_instance === null)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	*/

    /**
     * 得到缓存信息
     *
     * @param string $id       #缓存ID
     * @param arrar  $holidays #节假日配置
     */
    public function get($id)
    {
        //缓存文件不存在
        if(!$this->has($id))
        {
            return false;
        }
        $file = $this->_file($id);

        $data = $this->_fileGetContents($file);
        
        if($data['expire'] == 0 || time() < $data['expire'])
        {
            return $data['contents'];
        }
        return false;
    }

    /**
     * 设置一个缓存
     *
     * @param string $id   缓存id
     * @param array  $data 缓存内容
     * @param int    $cacheLife 缓存时间 0为无限
     */
    public function set($id, $data, $cacheLife = 0)
    {
        $time  = time();
        $cache = array();
        $cache['contents'] = $data;
        $cache['expire']   = $cacheLife === 0 ? 0 : $time + $cacheLife;
        $cache['mtime']    = $time;

        $file = $this->_file($id);

        return $this->_filePutContents($file, $cache);
    }

    /**
     * 清除一条缓存
     *
     * @param string cache id   
     * @return void
     */  
    public function delete($id)
    {
        if(!$this->has($id))
        {
            return false;
        }
        $file = $this->_file($id);
        //删除该缓存
        return unlink($file);
    }

    /**
     * 判断缓存是否存在
     *
     * @param string $id cache_id
     * @return boolean true 缓存存在 false 缓存不存在
     */
    public function has($id)
    {
        $file = $this->_file($id);

        if(!is_file($file))
        {
            return false;
        }
        return true;
    }

    /**
     * 通过缓存id得到缓存信息路径
     * @param string $id
     * @return string 缓存文件路径
     */
    protected function _file($id)
    {
        $fileName = $this->_idToFileName($id);

        return $this->_options['cache_dir'] . $fileName;
    }  

    /**
     * 通过id得到缓存信息存储文件名
     *
     * @param  $id
     * @return string 缓存文件名
     */
    protected function _idToFileName($id)
    {
        $prefix   = $this->_options['prefix_key'];

        return $prefix . $id;
    }

    /**
     * 通过filename得到缓存id
     *
     * @param  $id
     * @return string 缓存id
     */
    protected function _fileNameToId($fileName)
    {
        $prefix    = $this->_options['prefix_key'];

        return preg_replace('/^' . $prefix . '(.*)$/', '$1', $fileName);
    }

    /**
     * 把数据写入文件
     *
     * @param string $file 文件名称
     * @param array  $contents 数据内容
     * @return bool
     */
    protected function _filePutContents($file, $contents)
    {
        if($this->_options['mode'] == 1)
        {
            $contents = serialize($contents);
        }
        else
        {
            $time = time();
            $contents = "<?php\n".
                    " // mktime: ". $time. "\n".
                    " return ".
                    var_export($contents, true).
                    "\n?>";
        }
        $result = false;
        $f = @fopen($file, 'w');

        if($f)
		{
            @flock($f, LOCK_EX);

            fseek($f, 0);

            ftruncate($f, 0);

            $tmp = @fwrite($f, $contents);

            if(!($tmp === false))
			{
                $result = true;
            }
            @fclose($f);
        }
        @chmod($file,0777);
        return $result;            
    }

    /**
     * 从文件得到数据
     *
     * @param  sring $file
     * @return boolean|array
     */
    protected function _fileGetContents($file)
    {
        if(!is_file($file))
        {
            return false;
        }

        if($this->_options['mode'] == 1)
        {
            $f = @fopen($file, 'r');
            @$data = fread($f,filesize($file));
            @fclose($f);
            return unserialize($data);
        }
        else
        {
            return include $file;
        }
    }

    /**
     * 设置缓存路径
     *
     * @param string $path
     * @return self
     */
    public function setCacheDir($path)
    {
        if (!is_dir($path))
		{
			exit(error_logs('file_cache: ' . $path.' 不是一个有效路径',$this->_options['log_path']));
        }
        if (!is_writable($path))
		{
			exit(error_logs('file_cache: 路径 "'.$path.'" 不可写',$this->_options['log_path']));
        }
        $path = rtrim($path,'/') . '/';

        return $this->_options['cache_dir'] = $path;
    }

    /**
     * 设置缓存文件前缀
     *
     * @param srting $prefix
     * @return self
     */
    public function setCachePrefix($prefix)
    {
        return $this->_options['prefix_key'] = $prefix;
    }

    /**
     * 设置缓存存储类型
     *
     * @param int $mode
     * @return self
     */
    public function setCacheMode($mode = 1)
    {
        if($mode == 1)
        {
            return $this->_options['mode'] = 1;
        }
        else
        {
            return $this->_options['mode'] = 2;
        }
    }

    /**
     * 删除所有缓存
     * @return boolean
     */
    public function flush()
    {
        $glob = @glob($this->_options['cache_dir'] . $this->_options['prefix_key']);

        if(empty($glob))
        {
            return false;
        }
        foreach ($glob as $v)
        {
            $fileName = basename($v);
            $id =  $this->_fileNameToId($fileName);
            $this->delete($id);
        }
        return true;
    }
    /**
     * 判断是否需要更新缓存
     *
     * @param  sring $file
     * @return boolean|array
     */
    protected function is_update_cache($file)
    {
        
    }
}
?>