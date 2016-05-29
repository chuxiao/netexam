<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_common
{
    public static $userinfo;
    public static $active_left;
    public static $play_histories;
    public function __construct()
    {
        pub_mod_common::set_links();
        self::$userinfo = pub_mod_auth::get_current_userinfo();
        tpl::assign('time', time());
        tpl::assign('userinfo', self::$userinfo);

    }

    //左侧活动列表
    public static  function get_left_active()
    {
    	//左侧 ·《幻想三国》声望礼 礼金03-27  6
    	self::$active_left = mod_active::get_activity(3,10);
    	tpl::assign('active_left', self::$active_left);

    }
    public static  function get_userinfo()
    {
    	//左侧 ·《幻想三国》声望礼 礼金03-27  6
    	self::$userinfo = pub_mod_auth::get_current_userinfo();
    	/**
    	 * 积分加入
    	 */
    	if(!empty(self::$userinfo))
    	{
    		$user_id = self::$userinfo['user_id'];
    		$current_info = pub_mod_user::get_one_user_details($user_id);
    		self::$userinfo['charge_points'] = $current_info['charge_points'];
    		self::$userinfo['face'] = $current_info['face'];
    	}
    	$GLOBALS['userinfo'] = self::$userinfo;
    	tpl::assign('userinfo', self::$userinfo);

    }
//
 /**
  * 玩过历史
  */
	 public static  function play_histories()
	 {
	 	$user_info = self::$userinfo;
	 	if(!empty($user_info))
	 	{
		    self::$play_histories = mod_game::play_histories($user_info['user_id']);
		    tpl::assign('play_histories', self::$play_histories);
	 	}
	}
/**
 *
 * 注册记录
 */
	public static  function greg_log($user_id,$game_id,$server_id)
	{
		$ip = util::get_client_ip();
		if(!empty($user_id))
		{
			if(!empty($_COOKIE['111g_sid'])&&!empty($_COOKIE['111g_aid'])&&!empty($_COOKIE['111g_gid'])&&!empty($_COOKIE['111g_ssid']))
			{
				error_log($user_id." ".$_COOKIE['111g_sid'] . " " . $_COOKIE['111g_aid'] . " ". $_COOKIE['111g_gid'] . " ". $_COOKIE['111g_ssid'] . " " . ip2long($ip) . " " . time() . "\n",
				 3, PATH_DATA . '/log/greg.log');
			}elseif(!empty($_COOKIE['111g_sid'])&&!empty($_COOKIE['111g_aid'])){
				error_log($user_id." ".$_COOKIE['111g_sid'] . " " . $_COOKIE['111g_aid'] . " " . ip2long($ip) . " " . time() . "\n",
				 3, PATH_DATA . '/log/reg.log');
			}
		}

	}






}
