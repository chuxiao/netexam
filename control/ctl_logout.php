<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_logout
{
    private static $title = "退出系统";
    public function __construct()
    {
        tpl::assign("title", self::$title);
    }

    public function index()
    {
        // 清空session信息
        pub_mod_auth::logout_cookie();
        exit(header("location: /?ct=login"));
    }
}
