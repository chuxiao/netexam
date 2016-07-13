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
        tpl::assign("time", time());
        tpl::display("register.tpl");
    }

    public function register()
    {
        $form = array();
        $form['account'] = req::item("account", '');
        $form['passwd'] = req::item("passwd", '');
        $form['re_passwd'] = req::item("passwd2", '');
        $form['nickname'] = req::item("nickname", '');
        $form['gender'] = req::item("gender", '');
        $form['birthday'] = req::item("birthday", '');
        $form['code'] = req::item("verify_code", '');
        $form['auth_code'] = req::item("auth_code", '');
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

    public function get_mobile_key()
    {
        $form['code'] = req::item("verify_code");
        $form['account'] = req::item("account");
        try
        {
            pub_mod_auth::check_captcha($form);
            // 发送手机验证码
            $mobile_key = pub_mod_auth::get_mobile_key();
            $msg = "【创蓝文化】您的验证码是: ".$mobile_key.",请立即使用.";
            $user_id = $fom['account'];
            $url = $GLOBALS['config']['mobile_key']['url'].'account='.$GLOBALS['config']['mobile_key']['account'].'&pswd='.$GLOBALS['config']['mobile_key']['passwd'].'&mobile='.$user_id.'&msg='.$msg.'&needstatus=true';
            $ret = file_get_contents($url);
            $filename = date("Ym").'/'.date("Ymd");
            log::add($filename, $user_id.'    '.$msg.'    '.$ret);

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
