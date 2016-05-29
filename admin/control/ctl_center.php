<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_center
{
    public function __construct()
    {
    }

    public function index()
    {
        tpl::assign("title", "测试中心");
        tpl::display("center.tpl");
    }
}
