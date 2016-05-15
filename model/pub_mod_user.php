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
        $user_id = self::get_one_user_id($account);
        $user = self::get_one_user($user_id);
        $details = self::get_one_user_details($user_id);
        $data = array_merge($user, $details);
        return $data;
    }

    /**
     * 获取用户ID
     * @param type $value
     * @param type $type
     * @param type $details
     */
    public static function get_one_user_id($account)
    {
        $table_name = pub_mod_common::get_split_table($account, "115_login");
        $sql = "SELECT * FROM `{$table_name}` WHERE `login_name`='{$account}'";
        $login = db::get_one($sql);
        $user_id = $login['user_id'];
        return $user_id;
    }

    /**
     * 获取用户信息
     *
     * @param mixed $user user_id 、 email 、 user_name 、 mobile
     * @param string $type 类型
     * @param boolean $is_master 是否查询主数据库
     * @return array
     */
    public static function get_one_user($user_id)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "115_user");
        $sql = "SELECT * FROM `{$table_name}` WHERE `user_id`='{$user_id}'";
        $return = db::get_one($sql);
        return $return;
    }

    /**
     * 获取用户详细信息
     *
     * @param int $user_id
     * @return array
     */
    public static function get_one_user_details($user_id)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "115_user_details");
        $sql = "SELECT * FROM `{$table_name}` WHERE `user_id`='{$user_id}'";
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


    public static function account_exist($item_value, $user_id = 0, $item = '', $is_cache = false)
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

        $table_name = pub_mod_common::get_split_table($item_value, "115_login");
        $sql        = "SELECT * FROM  $table_name WHERE `login_name` = '{$item_value}'";
        $temp       = db::get_one($sql);
        if ($temp)
        {
            //如果传用户ID过来，则用用户ID和用邮箱从数据库取出来的用户ID对比一下，证实是同一个人
            if ($user_id && $user_id == $temp['user_id'])
            {
                return false;
            }
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
        $table_name = pub_mod_common::get_split_table($params['user_id'], "115_user");

        return db::insert($params, $table_name);
    }

    /**
     * 修改注册用户
     *
     * @param array $data
     * @return boolean
     */
    public static function update_user($user_id, $params)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "115_user");

        return db::update($params, $table_name);
    }


    /**
     * 添加用户信息
     *
     *
     * @param array $data
     * @return boolean
     */
    public static function insert_user_details($user_id, $params)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "115_user_details");

        return db::insert($params, $table_name);
    }

    /**
     * 修改用户信息
     *
     *
     * @param array $data
     * @return boolean
     */
    public static function update_user_details($user_id, $params)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "115_user_details");

        return db::update($params, $table_name, "`user_id`='{$user_id}'");
    }

    /**
     * 添加用户登录信息
     *
     *
     * @param array $data
     * @return boolean
     */
    public static function insert_login($params)
    {
        $table_name = pub_mod_common::get_split_table($params['login_name'], "115_login");

        return db::insert($params, $table_name);
    }

     /**
     * 全部渠道列表
     */
    public static function get_canal_list()
    {
    	 $sql = "SELECT id,canal_name FROM `canal` ORDER BY `id`";
         db::query($sql);
         $canal_list = db::fetch_all();
         if($canal_list)
         {
            foreach($canal_list as $k=>$v){
            	$canal_options[$v['id']] = $v['canal_name'];
       		}
         }
         return $canal_options;
    }

     /**
     * 获得渠道名
     */
    public static function get_canal_name($canal_id)
    {
    	$sql = "SELECT `canal_name` FROM `canal` where id='{$canal_id}' limit 0,1";
		db::query($sql);
        $count = db::fetch_one();
        return $count['canal_name'];
    }
    /**
     *
     */
    public static function account_true($user_id,$account, $email)
    {
    	$table_name = pub_mod_common::get_split_table($user_id, "115_user");
        $sql        = "SELECT * FROM  $table_name WHERE `user_name` = '{$account}' and `email`='{$email}'";
        $temp       = db::get_one($sql);
        if(!empty($temp))
        {
        	return true;
        }else{
        	return false;
        }
    }
    /**
     *h获取渠道下的用户列表
     */
    public static function canal_user($canal_id)
    {
        for($i=0;$i<=4;$i++)
        {
             $table_name = "115_user_details_0".$i;
             $sql        = "SELECT user_id FROM $table_name where reg_canal='{$canal_id}'";
             db::query($sql);
             $user_list[$i] = db::fetch_all();
        }
        if(!empty($user_list))
        {
        	return $user_list;
        }else{
        	return false;
        }

    }


}
