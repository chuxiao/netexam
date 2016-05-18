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
        // TODO: 清空session信息
        exit(header("location: /?ct=login"));
    }
}
