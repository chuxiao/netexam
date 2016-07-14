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
    private static $cookie_last_account = MYAPI_COOKIE_LAST_LOGIN; /* 最后一次登录用的账号名称,显示在登录框中 */
    private static $cookie_captcha      = MYAPI_COOKIE_CAPTCHA;
    private static $cookie_user_code    = MYAPI_COOKIE_ACCOUNT_CODE;


    /**
     * 检查验证码
     *
     */
    public static function check_captcha($data)
    {
        // 验证码不能为空
        if (empty($data['code']))
        {
            throw new Exception(serialize(array('code' => '验证码不能为空')));
        }
        $code = new mod_captcha;
        $value  = $code->authcode($_COOKIE[self::$cookie_captcha], 'DECODE', $GLOBALS['config']['cookie_pwd']);
        if (strtolower($data['code'])!=strtolower($value))
        {
            throw new Exception(serialize(array('code' => '验证码不正确或超时')));
        }
        if (empty($data['account']))
        {
            throw new Exception(serialize(array('account' => '请输入手机呈码')));
        }
        return true;
    }

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
        $code = new mod_captcha;
        $value  = $code->authcode($_COOKIE[self::$cookie_captcha], 'DECODE', $GLOBALS['config']['cookie_pwd']);
        if (strtolower($data['code'])!=strtolower($value))
        {
            throw new Exception(serialize(array('code' => '验证码不正确或超时')));
        }
        if (empty($data['account']))
        {
            throw new Exception(serialize(array('account' => '请输入手机呈码')));
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
        $code = new mod_captcha;
        $value  = $code->authcode($_COOKIE[self::$cookie_captcha], 'DECODE', $GLOBALS['config']['cookie_pwd']);
        if (strtolower($data['code'])!=strtolower($value))
        {
            throw new Exception(serialize(array('code' => '验证码不正确或超时')));
        }
        if (empty($data['account']))
        {
            throw new Exception(serialize(array('account' => '请输入手机呈码')));
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
        $passwd = strlen($data['passwd']) == 40 ? $data['passwd'] : sha1($data['passwd']);
        if ($result['passwd'] !== $passwd)
        {
            throw new Exception(serialize(array('account' => '密码错误')));
        }

        /* 禁止登陆 */
        if ($result['is_login'] < 1)
        {
            throw new Exception(serialize(array('account' => '帐号被禁')));
        }
        pub_mod_user::update_user_login($data['account'], $data['ip'], $data['login_time']);
        self::$curr_user_id = $result['user_id'];
        self::$curr_user = $result;
        self::$curr_user['user_id'] = $result['user_id'];
        self::$curr_user['gender'] = isset($result['gender']) ? $result['gender'] : 1;
        self::$curr_user['face'] = !empty($result['face']) ? "/static/uploads/small/".$result['face']."_small.jpg" : "/static/images/tx50.png";

        // 登陆时间，默认为0，即浏览器关闭cookie失效，选择记住密码则不为0，MYAPI_COOKIE_EXPIRE这个时间段内不失效
        $cookie_expire = !empty($data['time']) ? time() + MYAPI_COOKIE_EXPIRE : 0;
        // 设置Cookie
        self::set_logined_cookie(self::$curr_user, $cookie_expire, false);
        return true;
    }

    /**
     * 用户手机验证码登录验证，使用 Cookie 记录登录用户
     *
     *
     * @param array $data
     * @return boolean
     */
    public static function authenticate2($data)
    {
        // 验证码不能为空
        if (empty($data['code']))
        {
            throw new Exception(serialize(array('code' => '验证码不能为空')));
        }
        if (!isset($_COOKIE[self::$cookie_user_code]))
        {
            throw new Exception(serialize(array('code' => '手机验证码失效')));
        }
        $cookie_decode = self::_decrypt($_COOKIE[self::$cookie_user_code], self::$encrpy_key);
        $cookie_data   = explode(":", $cookie_decode);
        $md5_str       = array_pop($cookie_data);
        // 验证签名
        if (md5(implode(":", $cookie_data) . self::$sign_key) != $md5_str)
        {
            throw new Exception(serialize(array('code' => '手机验证码验证失败')));
        }
        if (time() - $cookie_data['timestamp'] > MYAPI_COOKIE_ACCOUNT_CODE_EXPIRE)
        {
            throw new Exception(serialize(array('code' => '手机验证码超时，请重新获取')));
        }
        $code = new mod_captcha;
        $value  = $code->authcode($cookie_data['acaptcha'], 'DECODE', $GLOBALS['config']['cookie_pwd']);
        if (strtolower($data['code'])!=strtolower($value))
        {
            throw new Exception(serialize(array('code' => '手机验证码不正确')));
        }

        $result = pub_mod_user::get_one_userinfo($data['account']);
        if (empty($result))
        {
            throw new Exception(serialize(array('account' => '帐号不存在')));
        }

        /* 禁止登陆 */
        if ($result['is_login'] < 1)
        {
            throw new Exception(serialize(array('account' => '帐号被禁')));
        }
        pub_mod_user::update_user_login($result['user_id'], $data['ip'], $data['login_time']);
        file_put_contents("/tmp/log.txt", var_export($result, true), FILE_APPEND);
        self::$curr_user_id = $result['user_id'];
        self::$curr_user = $result;
        self::$curr_user['user_id'] = $result['user_id'];
        self::$curr_user['gender'] = isset($result['gender']) ? $result['gender'] : 1;
        self::$curr_user['face'] = !empty($result['face']) ? "/static/uploads/small/".$result['face']."_small.jpg" : "/static/images/tx50.png";

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
        $cookie_data = array($params['user_id'], rawurlencode($params['nickname']), $params['gender'], $params['face'], $params['points']);
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
                "nickname" => rawurldecode($cookie_data[1]),
                "gender" => $cookie_data[2],
                "face" => $cookie_data[3],
                "points" => $cookie_data[4]);
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
     * 生成图片验证码随机字母
     *
     */
    public static function make_verify_code()
    {
        $code = new mod_captcha;
        $code->code = $code->make_seccode(4);                           // 验证码
        $cookie = $code->authcode($code->code, 'ENCODE', $GLOBALS['config']['cookie_pwd']);
        setcookie(self::$cookie_captcha, $cookie, 0);
        $code->type = 0;                                               // 0英文图片验证码 1中文图片验证码 2Flash 验证码 3语音验证码 4位图验证码
        $code->width = 100;                                            // 验证码宽度
        $code->height = 30;                                            // 验证码高度
        $code->background = 1;                                         // 是否随机图片背景
        $code->adulterate = 1;                                         // 是否随机背景图形
        $code->ttf = 0;                                                // 是否随机使用ttf字体
        $code->angle = 0;                                              // 是否随机倾斜度
        $code->warping = 0;                                            // 是否随机扭曲
        $code->scatter = 0;                                            // 是否图片打散
        $code->color = 1;                                              // 是否随机颜色
        $code->size = 0;                                               // 是否随机大小
        $code->shadow = 1;                                             // 是否文字阴影
        $code->animator = 0;                                           // 是否GIF 动画
        $code->fontpath = './static/captcha/image/seccode/font/';      // 字体路径
        $code->datapath = './static/captcha/image/seccode/';           // 数据路径
        $code->display();
    }

    /**
     * 生成手机验证码
     *
     */
    public static function make_account_code($account)
    {
        $code = new mod_captcha;
        $auth_code = $code->make_seccode(6);
        $cookie_data = array('user_id' => $account, 'timestamp' => time(), 'acaptcha' => $code->authcode($auth_code, 'ENCODE', $GLOBALS['config']['cookie_pwd']), 0);
        $cookie_expire = time() + MYAPI_COOKIE_ACCOUNT_CODE_EXPIRE;
        $value         = implode(':', $cookie_data);
        /* 签名 */
        $value .=":" . md5($value . self::$sign_key);
        /* 加密 */
        $value         = self::_encrpy($value, self::$encrpy_key);

        // 记录最后一次登录成功的账号
        setcookie(self::$cookie_user_code, $value, $cookie_expire, '/', self::$cookie_domain);
        return $auth_code;
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
