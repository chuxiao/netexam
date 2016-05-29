<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * 管理菜单读取
 *
 * @version $Id$
 */
class pub_mod_user
{
    /**
     * 获取用户详细信息
     * @param type $value
     * @param type $type
     * @param type $details
     */
    public static function get_one_userinfo($account)
    {
        $user = self::get_one_user($account);
        return $user;
    }

    /**
     * 获取用户信息
     *
     * @param mixed $user user_id 、 email 、 user_name 、 mobile
     * @param string $type 类型
     * @param boolean $is_master 是否查询主数据库
     * @return array
     */
    public static function get_one_user($account)
    {
        $sql = "SELECT * FROM user_admin WHERE `user_name`='{$account}'";
        $return = db::get_one($sql);
        return $return;
    }

    public static function account_exist($item_value, $account = 0, $item = '', $is_cache = false)
    {
        $cache = array();
        if ($is_cache)
        {
            $cache = cache::get('account', $item_value);
        }
        if ($cache)
        {
            return $cache;
        }

        $sql        = "SELECT * FROM user_admin WHERE `user_name` = '{$item_value}'";
        $temp       = db::get_one($sql);
        if ($temp)
        {
            cache::set('account', $item_value, $temp);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 添加注册用户
     *
     * @param array $data
     * @return boolean
     */
    public static function insert_user($params)
    {
        return db::insert($params, 'user_admin');
    }

    /**
     * 修改注册用户
     *
     * @param array $data
     * @return boolean
     */
    public static function update_user($account, $params)
    {
        return db::update($params, 'user_admin', "`user_name` = '{$account}'");
    }

    /**
     * 更新玩家登录信息
     *
     *
     * @param string account
     * @param int ip
     * @param int time
     */
    public static function update_user_login($account, $ip, $time)
    {
        self::update_user($account, array('last_ip' => ip2long($ip), 'last_login' => $time));
    }
}
