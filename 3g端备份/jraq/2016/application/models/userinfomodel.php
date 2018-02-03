<?php 
class UserInfoModel 
{
    
    private $userinfo = null;

    /**
     * @getUserInfo 获取用户信息
     *
     * @access public
     * @return array
     */
    public function getUserInfo() 
    {
        return $this->getUserCookie();
    }

    /**
     * 获取用户COOKIE信息
     * @access private
     * @return array
     */
    private function getUserCookie() 
    {
        $this->userinfo['uid']   = is_numeric($_COOKIE['Usr_ID']) ? $_COOKIE['Usr_ID'] : 0;
        $this->userinfo['rid']   = is_numeric($_COOKIE['Usr_RoleID']) ? $_COOKIE['Usr_RoleID'] : 0;
        $this->userinfo['uname'] = trim($_COOKIE['RealName']) ? $_COOKIE['RealName'] : '匿名';
        
        return $this->userinfo;
    }
}