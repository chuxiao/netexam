<?php
if( !defined('CORE') ) exit('Request Error!');

/**
 * 用户权限业务逻辑
 *
 * @author yangzetao <yangzetao@ylmf.com>
 * @version $Id: pub_mod_auth.php 1 2012-08-07 07:26:04Z heguangzhong $
 */
class pub_mod_auth
{
    public static $is_login             = null; /* 是否登录 */
    public static $curr_user_id         = null;

    /**
     * 检查用户名和验证码
     *
     */
    public static function check_user_and_captcha($data)
    {
        // 验证码不能为空
        if (empty($data['code']))
        {
            throw new Exception(serialize(array('code' => '验证码不能为空')));
        }
        $code = new cls_securimage;
        if (!$code->check($data['code']))
        {
            throw new Exception(serialize(array('code' => '验证码不正确或超时')));
        }
        if (empty($data['account']))
        {
            throw new Exception(serialize(array('account' => '请输入账号')));
        }
        $result = pub_mod_user::get_one_userinfo($data['account']);
        if (empty($result))
        {
            throw new Exception(serialize(array('account' => '帐号不存在')));
        }
        return true;
    }
    /**
     * 用户登录验证，使用 Cookie 记录登录用户
     *
     *
     * @param array $data
     * @return boolean
     */
    public static function authenticate($data)
    {
        // 验证码不能为空
        if (empty($data['code']))
        {
            throw new Exception(serialize(array('code' => '验证码不能为空')));
        }
        $code = new cls_securimage;
        if (!$code->check($data['code']))
        {
            throw new Exception(serialize(array('code' => '验证码不正确或超时')));
        }
        if (empty($data['account']))
        {
            throw new Exception(serialize(array('account' => '请输入账号')));
        }
        if (empty($data['passwd']))
        {
            throw new Exception(serialize(array('passwd' => '请输入密码')));
        }

        $result = pub_mod_user::get_one_userinfo($data['account']);
        if (empty($result))
        {
            throw new Exception(serialize(array('account' => '帐号不存在')));
        }

        // 密码不正确
        if ($result['passwd'] != $data['passwd'])
        {
            throw new Exception(serialize(array('account' => '密码错误')));
        }

        /* 禁止登陆 */
        if ($result['is_login'] < 1)
        {
            throw new Exception(serialize(array('account' => '帐号被禁')));
        }
        pub_mod_user::update_user_login($data['account'], $data['ip'], $data['login_time']);
        $_SESSION['account'] = $data['account'];
        $_SESSION['user_id'] = $result['user_id'];
        return true;
    }

    /**
     * 用户是否在登录状态
     *
     * @return boolean
     */
    public static function is_login()
    {
        if (!is_null(self::$is_login))
        {
            return self::$is_login;
        }
        self::get_current_userinfo();
        return self::$is_login;
    }

    /**
     * 获取当前登录的用户信息（COOKIE）
     *
     * @return int
     */
    public static function get_current_user_id()
    {
        session_start();
        return $_SESSION['user_id'];
    }

    /**
     * 从cookie中取得当前用户的基本资料,包括用户ID,用户名,email,登录时间
     * 更详细的资料通过cls_my_user::get_one_user取
     */
    public static function get_current_userinfo()
    {
        session_start();
        if (isset($_SESSION['user_id']))
        {
            self::$is_login = true;
        }
        else
        {
            self::$is_login = false;
        }
    }

    /**
     * 退出 Session
     *
     * @return void
     */
    public static function logout_session()
    {
        if (!session_id())
        {
            session_start();
        }
        /* 将用户session清空 */
        $_SESSION = array();
    }

}
