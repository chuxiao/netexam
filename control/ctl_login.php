<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_login
{
    private static $title = "请登录金顿在线测试平台";
    public function __construct()
    {
        tpl::assign("title", self::$title);
    }

    public function index()
    {
        $time = time();
        tpl::assign("time", $time);
        tpl::display("login.tpl");
    }

    public function auth()
    {
        // TODO:
    }

    public function find_passwd()
    {
        tpl::display("login.find_passwd.tpl");
    }

    public function auth2()
    {
        // TODO:
    }

    public function reset_passwd()
    {
        // TODO:
    }
}
