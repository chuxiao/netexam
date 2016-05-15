<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_login
{
    public function __construct()
    {
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
