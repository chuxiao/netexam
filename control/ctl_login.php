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
        tpl::display("login.tpl");
    }

    public function auth()
    {
        // TODO:
    }
}
