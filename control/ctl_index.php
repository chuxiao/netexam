<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_index
{
    public function __construct()
    {
    }

    public function index()
    {
        if (!pub_mod_auth::is_login())
        {
            exit(header("location: /?ct=login"));
        }
        else
        {
            exit(header("location: /?ct=center"));
        }
    }
}
