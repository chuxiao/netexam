<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * 管理菜单读取
 *
 * @version $Id$
 */
class pub_mod_register
{
    public static function verify($data)
    {
        // 验证码不能为空
        if (empty($data['code']))
        {
            throw new Exception(serialize(array('code' => '验证码不能为空')));
        }
        else
        {
            if(!isset($_COOKIE['captcha']))
            {
                throw new Exception(serialize(array('code' => '生成验证码超时')));
            }
            $code = new mod_captcha;
            $value  = $code->authcode($_COOKIE['captcha'], 'DECODE', $GLOBALS['config']['cookie_pwd']);
            if (strtolower($data['code'])!=strtolower($value))
            {
                throw new Exception(serialize(array('code' => '验证码不正确或超时')));
            }
        }

        //验证用户名是否为空
        if (empty($data['account']))
        {
            throw new Exception(serialize(array('account' => '请输入用户名')));
        }
        //验证用户名是否被注册
        if (pub_mod_user::account_exist($data['account']))
        {
            throw new Exception(serialize(array('account' => '该用户已被注册')));
        }

        // 验证密码是否为空
        if (empty($data['passwd']))
        {
            throw new Exception(serialize(array('passwd' => '密码不能为空')));
        }

        // 密码长度错误
        if (!cls_validate::len($data['passwd'], 6, 20))
        {
            throw new Exception(serialize(array('passwd' => '密码长度错误')));
        }

        //两次输入密码是否相同
        if ($data['passwd'] != $data['re_passwd'])
        {
            throw new Exception(serialize(array('passwd' => '两次输入密码不同')));
        }

        //昵称不能为空
        if (empty($data['nickname']))
        {
            throw new Exception(serialize(array('nickname' => '真实姓名不能为空')));
        }

        return $data;
    }

    /**
     * 注册用户模块
     *
     * @param array $data
     * @return boolean
     */
    public static function create_account($data)
    {
        if (empty($data))
        {
            return false;
        }

        // 初始化用户基本数据
        $user_id = $data['account'];
        $data_user         = array();
        $data_user['user_id']   = $data['account'];
        $data_user['type'] = 1;
        // 移动端直接传递sha1加密值过来
        $data_user['passwd']    = strlen($data['passwd']) == 40 ? $data['passwd'] : sha1($data['passwd']);
        $data_user['is_login'] = 1;

        $handle = pub_mod_user::insert_user($data_user);
        if ($handle)
        {
            // 初始化用户详细数据
            $data_details = array();
            $data_details['user_id']      = $data['account'];
            $data_details['reg_ip']       = ip2long(util::get_client_ip());   // 注册IP
            $data_details['reg_time']     = time();                                // 注册时间
            $data_details['name']         = $data['nickname'];             // 真实姓名
            $data_details['points']       = 0;

            $handle = pub_mod_user::insert_user_details($user_id, $data_details);
            if (!$handle)
            {
                throw new Exception(serialize(array('error' => 'insert_user_details error')));
            }
        }
        else
        {
            throw new Exception(serialize(array('error' => 'insert_user error')));
        }

        return $user_id;
    }
}
