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
        $time = time();
        tpl::assign("time", $time);
        tpl::assign("title", "管理后台-登录");
        tpl::display("login.tpl");
    }

    public function auth()
    {
        // 登陆表单
        $form['time']   = empty($form['time']) ? 0 : 1; // 保持登陆
        $form['ip']     = util::get_client_ip();
        $form['code']   = req::item('verify_code');
        $form['passwd'] = req::item('passwd');
        $form['account'] = req::item('account');
        $form['login_time'] = time();
        try
        {
            // 登陆行为
            pub_mod_auth::authenticate($form);
            cls_msgbox::show('登录成功', '正在为您跳转......', '/admin/?ct=center');
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
            }
            else
            {
                cls_msgbox::show('出错了', $e->getCode());
            }
        }
    }

    public function verifycode()
    {
        $vdimg = new cls_securimage;
        $vdimg->show();
    }

    public function logout()
    {
        pub_mod_auth::logout_session();
        exit(header("location: /admin/?ct=login"));
    }
}
