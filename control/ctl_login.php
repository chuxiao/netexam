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
            tpl::assign("title", "重置密码");
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
            $passwd = req::item("passwd", "");
            $passwd2 = req::item("passwd2", "");
            if ($passwd != $passwd2)
            {
                cls_msgbox::show('出错了', '两次密码输入不一致');
            }
            else
            {
                $account = pub_mod_auth::get_user_id();
                $new_passwd = strlen($passwd) == 40 ? $passwd : sha1($passwd);
                $params = array("passwd" => $new_passwd);
                pub_mod_user::update_user($account, $params);
                cls_msgbox::show('登录成功', '正在为您跳转......', '/?ct=login');

            }

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
            // 发送手机验证码
            $user_id = $form['account'];
            $mobile_key = pub_mod_auth::make_account_code($user_id);
            $msg = "【创蓝文化】您的验证码是: ".$mobile_key.",请立即使用.";
            require_once PATH_LIBRARY . '/ChuanglanSmsHelper/ChuanglanSmsApi.php';
            $clapi  = new ChuanglanSmsApi();
            $result = $clapi->sendSMS($user_id, $msg,'true');
            $result = $clapi->execResult($result);
            $filename = date("Ym").'/'.date("Ymd");
            log::add($filename, $user_id.'    '.$mobile_key.'    '.$result[1]);

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
