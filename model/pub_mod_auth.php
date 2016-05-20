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
    public static $curr_user            = array();
    public static $curr_user_id         = null;
    public static $is_p3p_header        = false;
    private static $cookie_domain       = MYAPI_COOKIE_DOMAIN; /* AUTH COOKIE 域名 */
    private static $cookie_auth         = MYAPI_COOKIE; /* 登录成功的COOKIE，保存user_id, user_name, email */
    private static $cookie_session      = MYAPI_COOKIE_SESSION; /* 登录后的校验 */
    private static $cookie_online       = MYAPI_COOKIE_ONLINE; /* 在线 */
    private static $sign_key            = MYAPI_SIGN_KEY;
    private static $encrpy_key          = MYAPI_ENCRYPT_KEY;
    private static $cookie_last_account = "OOFL"; /* 最后一次登录用的账号名称,显示在登录框中 */

    /**
     * 用户登录验证，使用 Cookie 记录登录用户
     *
     *
     * @param array $data
     * @return boolean
     */
    public static function authenticate($data, $is_register = false)
    {
        if (!$is_register)
        {
            // 验证码不能为空
            if (empty($data['code']))
            {
                throw new Exception(serialize(array('code' => '验证码不能为空')));
            }
            else
            {
                $code = new mod_captcha;
                $value  = $code->authcode($_COOKIE['captcha'], 'DECODE', $GLOBALS['config']['cookie_pwd']);
                if (strtolower($data['code'])!=strtolower($value))
                {
                    throw new Exception(serialize(array('code' => '验证码不正确或超时')));
                }
            }
        }
        if (empty($data['account']))
        {
            throw new Exception(serialize(array('account' => '请输入手机呈码')));
        }

        if (empty($data['passwd']))
        {
            throw new Exception(serialize(array('passwd' => '请输入密码')));
        }

        // 第一步：查询IP是否已封禁
        /*if (!pub_mod_login::v_ip($data['ip']))
        {
            T(10003);
        }

        // IP是否被后台禁用
        if (pub_mod_user::exist_forbid_ip(ip2long($data['ip'])))
        {
            T(10105);
        }*/
        $result = pub_mod_user::get_one_userinfo($data['account']);
        if (empty($result))
        {
            throw new Exception(serialize(array('account' => '帐号不存在')));
        }

        // 第二步：帐号错误失败次数
        /*if (!pub_mod_login::v_times_by_account($data['account']))
        {
            T(10001);
        }

        // 第二步：帐号IP失败次数
        if (!pub_mod_login::v_times_by_ip($data['ip']))
        {
            T(10002);
        }*/

        // 密码不正确
        $passwd = strlen($data['passwd']) == 40 ? $data['passwd'] : sha1($data['passwd']);
        if ($result['passwd'] !== $passwd)
        {
            throw new Exception(serialize(array('account' => '密码错误')));
            /* 内网暂时不校验密码登录, 外网一定要注意这里 */
            //pub_mod_login::add_times($data['account'], ip2long($data['ip']));
            //T(10005);
        }

        /* 禁止登陆 */
        if ($result['is_login'] < 1)
        {
            throw new Exception(serialize(array('account' => '帐号被禁')));
        }
        $result['login_ip']   = util::get_client_ip();
        $result['last_login'] = $result['last_login'];
        $result['app']        = !empty($data['app']) ? $data['app'] : "undefined";

        // 登陆成功后，清除登录失败次数
        self::$curr_user_id = $result['user_id'];
        self::$curr_user = $result;
        self::$curr_user['last_account'] = $data['account'];
        self::$curr_user['mobile'] = $result['mobile'];
        self::$curr_user['ip'] = $result['login_ip'];
        self::$curr_user['user_id'] = $result['user_id'];
        self::$curr_user['user_name'] = $result['user_name'];
        self::$curr_user['email'] = $result['email'];
        self::$curr_user['email_verify'] = isset($result['email_verify']) ? $result['email_verify'] : 0;
        self::$curr_user['gender'] = isset($result['gender']) ? $result['gender'] : 1;
        self::$curr_user['face'] = !empty($result['face']) ? "/static/uploads/small/".$result['face']."_small.jpg" : "/static/images/tx50.png";
        /*
        // 记录登陆队列
        $params_login = array();
        $params_login["user_id"] = $result['user_id'];
        $params_login["time"]    = time();
        $params_login["ip"]      = $data['ip'];
        $params_login["from"]    = $data['app'];
        pub_mod_login::add_loginlog($params_login);
         */


        // 登陆时间，默认为0，即浏览器关闭cookie失效，选择记住密码则不为0，MYAPI_COOKIE_EXPIRE这个时间段内不失效
        $cookie_expire = !empty($data['time']) ? time() + MYAPI_COOKIE_EXPIRE : 0;
        // 设置Cookie
        self::set_logined_cookie(self::$curr_user, $cookie_expire, false);
        return true;
    }

    /**
     * 设置已登录用户的 auth cookie
     *
     * @param int $user_id
     * @param int $expire_time 过期时间
     * @param bool $return_cookie 是否返回生成的cookie
     * @return void
     */
    public static function set_logined_cookie($params = array(), $cookie_expire = -1, $return_cookie = false)
    {
        if (empty($params))
        {
            return false;
        }
        self::p3pheader();
        $cookie_data = array($params['user_id'], rawurlencode($params['user_name']), $params['email'], time(),$params['face'],$params['gender']);
        $cookie_expire = $cookie_expire == 0 ? 0 : time() + MYAPI_COOKIE_EXPIRE;
        $value         = implode(':', $cookie_data);
        /* 签名 */
        $value .=":" . md5($value . self::$sign_key);
        /* 加密 */
        $value         = self::_encrpy($value, self::$encrpy_key);
        if ($return_cookie)
        {
            return $value;
        }

        $last_account = $params['email'];
        if ($params['mobile'])
        {
            $last_account = $params['mobile'];
        }

        // 记录最后一次登录成功的账号
        setcookie(self::$cookie_last_account, $last_account, time() + MYAPI_COOKIE_EXPIRE, '/', self::$cookie_domain);
        // 设置Cookie
        setcookie(self::$cookie_auth, $value, $cookie_expire, '/', self::$cookie_domain);

        // 返回
        return true;
    }

    /**
     * 输出php header(防止重复)
     *
     * @return void
     */
    public static function p3pheader()
    {
        if (!self::$is_p3p_header)
        {
            header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
            self::$is_p3p_header = true;
        }
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
     * 获取当前用户UID
     *
     * @return int
     */
    public static function get_user_id()
    {
        if (!is_null(self::$curr_user_id))
        {
            return self::$curr_user_id;
        }
        self::get_current_userinfo();
        return self::$curr_user_id;
    }

    /**
     * 获取当前登录的用户信息（COOKIE）
     *
     * @return int
     */
    public static function get_current_user_id()
    {
        if (!is_null(self::$curr_user_id))
        {
            return self::$curr_user_id;
        }
        self::get_current_userinfo();
        return self::$curr_user_id;
    }

    /**
     * 从cookie中取得当前用户的基本资料,包括用户ID,用户名,email,登录时间
     * 更详细的资料通过cls_my_user::get_one_user取
     */
    public static function get_current_userinfo()
    {
        if (!isset($_COOKIE[self::$cookie_auth]))
        {
            return false;
        }
        // 解密
        $cookie_decode = self::_decrypt($_COOKIE[self::$cookie_auth], self::$encrpy_key);
        $cookie_data   = explode(":", $cookie_decode);
        $md5_str       = array_pop($cookie_data);
        // 验证签名
        if (md5(implode(":", $cookie_data) . self::$sign_key) != $md5_str)
        {
            self::logout_cookie();
            return false;
        }
        else
        {
            self::$curr_user = array("user_id" => $cookie_data[0],
                "user_name" => rawurldecode($cookie_data[1]),
                "email" => $cookie_data[2],
                "last_login" => $cookie_data[3],
                "face" => $cookie_data[4],
                "gender" => $cookie_data[5]);
            self::$curr_user_id = $cookie_data[0];
            self::$is_login = true;
            return self::$curr_user;
        }
    }

    /**
     * 加密Cookie
     *
     * @param string $txt
     * @param string $key
     * @return string
     */
    private static function _encrpy($txt, $key)
    {
        $key_md5 = md5($key);
        $encode  = "";
        for ($i = 0; $i < strlen($txt); $i++)
        {
            $j = $i % strlen($key_md5);
            $encode .= $txt[$i] ^ $key_md5[$j];
        }
        return rawurlencode($encode);
    }

    /**
     * 解密Cookie
     *
     * @param string $txt
     * @param string $key
     * @return string
     */
    private static function _decrypt($txt, $key)
    {
        $txt     = rawurldecode($txt);
        $key_md5 = md5($key);
        $decode  = "";
        for ($i = 0; $i < strlen($txt); $i++)
        {
            $j = $i % strlen($key_md5);
            $decode .= $key_md5[$j] ^ $txt[$i];
        }
        return $decode;
    }

    //获取上次登录帐号
    public static function get_last_account()
    {
        return isset($_COOKIE[self::$cookie_last_account]) ? $_COOKIE[self::$cookie_last_account] : false;
    }

    /**
     * 退出 Cookie
     *
     * @retun boolean
     */
    public static function logout_cookie()
    {
        self::p3pheader();
        setcookie(self::$cookie_auth, '', time() - 86400, '/', self::$cookie_domain);
        setcookie(self::$cookie_online, '', time() - 86400, '/', self::$cookie_domain);
        return true;
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
