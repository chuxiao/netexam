<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_register
{
    private static $title = "注册用户";
    public function __construct()
    {
        tpl::assign("title", self::$title);
    }

    public function index()
    {
        tpl::display("register.tpl");
    }

    public function register()
    {
        $form = req::item("form", '');
        try
        {
            pub_mod_register::verify($form);
            pub_mod_register::create_account($form);
            cls_msgbox::show('注册成功', '正在为您跳转......', '/?ct=login');
        }
        catch (Exception $e)
        {
            if ($arr_error = @unserialize($e->getMessage()))
            {
                foreach ($arr_error as $key=>$value)
                {
                    $title = $key;
                    $content = $value;
                }
                cls_msgbox::show($title, $value, -1);
                exit;

                tpl::assign('error', $arr_error);
                tpl::assign('v', $form);
            }
            else
            {
                cls_msgbox::show('出错了', $e->getCode());
                exit;
            }
        }
    }

    public function verifycode()
    {
        pub_mod_auth::make_verify_code();
    }
}
