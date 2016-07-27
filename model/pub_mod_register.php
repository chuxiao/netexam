<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * 管理菜单读取
 *
 * @version $Id$
 */
class pub_mod_register
{
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
            $data_details['nickname']     = $data['nickname'];             // 真实姓名
            $data_details['points']       = 0;
            $data_details['gender']       = $data['gender'];
            $data_details['birthday']     = $data['birthday']."-01";

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
