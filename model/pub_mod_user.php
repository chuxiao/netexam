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
        $details = self::get_one_user_details($account);
        $data = array_merge($user, $details);
        return $data;
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
        $table_name = pub_mod_common::get_split_table($account, "user");
        $sql = "SELECT * FROM `{$table_name}` WHERE `user_id`='{$account}'";
        $return = db::get_one($sql);
        return $return;
    }

    /**
     * 获取用户详细信息
     *
     * @param int $account
     * @return array
     */
    public static function get_one_user_details($account)
    {
        $table_name = pub_mod_common::get_split_table($account, "user_details");
        $sql = "SELECT * FROM `{$table_name}` WHERE `user_id`='{$account}'";
        $return = db::get_one($sql);

        if ($return)
        {
            /* 安全密保 */
            if (!empty($return['safe_aq']) && $return['safe_aq']!="Array")
            {
                if (!is_array($return['safe_aq']))
                {
                    $return['safe_aq'] = unserialize($return['safe_aq']);
                }
            }
            /* 实名验证信息 */
            if (!empty($return['verify_info']) && $return['verify_info']!="Array")
            {
                $return['verify_info'] = unserialize($return['verify_info']);
            }
        }
        else
        {
            return false;
        }

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

        $table_name = pub_mod_common::get_split_table($item_value, "user");
        $sql        = "SELECT * FROM  $table_name WHERE `user_id` = '{$item_value}'";
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
        $table_name = pub_mod_common::get_split_table($params['user_id'], "user");

        return db::insert($params, $table_name);
    }

    /**
     * 修改注册用户
     *
     * @param array $data
     * @return boolean
     */
    public static function update_user($account, $params)
    {
        $table_name = pub_mod_common::get_split_table($account, "user");
        return db::update($params, $table_name, "`user_id` = '{$account}'");
    }


    /**
     * 添加用户信息
     *
     *
     * @param array $data
     * @return boolean
     */
    public static function insert_user_details($account, $params)
    {
        $table_name = pub_mod_common::get_split_table($account, "user_details");
        return db::insert($params, $table_name);
    }

    /**
     * 修改用户信息
     *
     *
     * @param array $data
     * @return boolean
     */
    public static function update_user_details($account, $params)
    {
        $table_name = pub_mod_common::get_split_table($account, "user_details");
        return db::update($params, $table_name, "`user_id`='{$account}'");
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
        self::update_user($account, array('last_ip' => util::ipton($ip), 'last_login' => $time));
    }
}
