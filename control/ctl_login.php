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
        tpl::assign("title", "登录");
        tpl::display("login.tpl");
    }

    public function auth()
    {
        try
        {
            // 登陆表单
            $form['time']   = empty($form['time']) ? 0 : 1; // 保持登陆
            $form['ip']     = util::get_client_ip();
            $form['code']   = req::item('verify_code');
            $form['passwd'] = req::item('passwd');
            $form['account'] = req::item('account');

            // 登陆行为
            if(pub_mod_auth::authenticate($form))
            {
                // P3P
                pub_mod_auth::p3pheader();
                $cookie_expire = ($form['time'] == 1) ? time() + MYAPI_COOKIE_EXPIRE : 0;
                cls_msgbox::show('登录成功', '正在为您跳转......', '/?ac=center');
            }
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

    public function find_passwd()
    {
        $time = time();
        tpl::assign("time", $time);
        tpl::display("login.find_passwd.tpl");
    }

    public function auth2()
    {
        // TODO:
        tpl::display("login.reset_passwd.tpl");
    }

    public function reset_passwd()
    {
        // TODO:
    }
}
