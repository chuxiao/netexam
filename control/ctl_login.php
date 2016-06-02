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
            // P3P
            pub_mod_auth::p3pheader();
            cls_msgbox::show('登录成功', '正在为您跳转......', '/?ct=center');
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
        tpl::assign("title", "找回密码");
        tpl::assign("time", $time);
        tpl::display("login.find_passwd.tpl");
    }

    public function auth2()
    {
        // 登陆表单
        $form['ip']     = util::get_client_ip();
        $form['code']   = req::item('verify_code');
        $form['login_time'] = time();
        $form['account'] = req::item('account');
        $form['auth_code'] = req::item('auth_code');
        try
        {
            // 登陆行为
            pub_mod_auth::authenticate2($form);
            // P3P
            pub_mod_auth::p3pheader();
            tpl::display("login.reset_passwd.tpl");
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

    public function reset_passwd()
    {
        if (pub_mod_auth::is_login())
        {
            // TODO:更新密码
        }
        else
        {
            cls_msgbox::show('出错了', '您尚未登录系统');
        }
    }

    public function get_mobile_key()
    {
        $form['code'] = req::item("verify_code");
        $form['account'] = req::item("account");
        try
        {
            pub_mod_auth::check_user_and_captcha($form);
            // TODO: 发送手机验证码
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
}
