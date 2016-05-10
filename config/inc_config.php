<?php
if( !defined('CORE') ) exit('Request Error!');

//-------------------------------------------------------------
//基本常量
//-------------------------------------------------------------
define('OPEN_DEBUG', false);

//如果开启了debug模式，仍然不想显示debug的信息（通常是ajax/api类接口），可以在操作的页面或控制器中把这个变量改为 true
define('DEBUG_HIDDEN', false);
define('PATH_MODEL', './model');
define('PATH_CONTROL', './control');
define('PATH_ROOT', substr(CORE, 0, -5) );
define('PATH_LIBRARY', CORE . '/library');
define('PATH_SHARE', CORE . '/share');
define('PATH_CONFIG', PATH_ROOT . '/config');
define('PATH_DATA', PATH_ROOT . '/data');
define('PATH_CACHE', PATH_DATA . '/cache');
define('PATH_DM_CONFIG', PATH_CONFIG . '/dm_config');


define('PATH_HTML', PATH_ROOT . '/html');  //静态文件生成目录
define('PATH_TEST_HTML', PATH_ROOT . '/test');  //静态文件生成 测试目录
define('SITE_URL', 'http://www.471wan.com/html');  //站点URL
define('HTML_URL', 'http://www.471wan.com/html');  //静态预览URL
define('HTML_TEST_URL', 'http://www.471wan.com/test');   //测试目录访问URL
//define('MYAPI_COOKIE_DOMAIN', '.www.471wan.com');

//正式环境中如果要考虑二级域名问题的应该用 .xxx.com
define('COOKIE_DOMAIN', '');

//主应用URL
define('URL', 'http://www.471wan.com');
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
//session类型 file || mysql || memcache
define('SESSION_TYPE', 'file');
//参数描述配置
define('WEBSITE', '471wan');
define('AUTHOR', '203');
define('TITLE', '网页游戏|471wan网页游戏平台-webgame-白领游戏-娱乐平台');
define('KEYWORDS', '网页游戏大全,网页游戏,最新网页游戏,好玩的网页游戏');
define('DESCRIPTION', '471wan网页游戏平台是专业的网页游戏娱乐平台,这里汇集了最新网页游戏和好玩的网页游戏!想玩什么,就玩什么.是白领们玩网页游戏的首选网页游戏大全网站!');
//------------------------------------------------------------------------------------------
//配置变量，或系统定义的全局性变量，建议都用 config 开头，在路由器中默认拦截这种变量名
//------------------------------------------------------------------------------------------
$GLOBALS['domain_key']='842df3d27cdded1a3bef7606e0ce5efc';
//调试选项（指定某些IP允许开启调试，数组格式为 array('ip1', 'ip2'...)
$GLOBALS['config']['safe_client_ip'] = array('127.0.0.1', '192.168.1.145','119.251.6.182','211.103.230.2140');
//网站日志配置
$GLOBALS['config']['log'] = array(
   'file_path' => PATH_DATA.'/log',
   'log_type'  => 'file',
);

//cache配置(df_prifix建议按网站名分开,如mc_114la_ / mc_tuan_ 等)
//cache_type一般是memcache，如无可用则用file，如有条件，用memcached
$GLOBALS['config']['cache'] = array(
    'enable'  => false,
    'cache_type' => 'memcache',
    'cache_time' => 7200,
    'file_cachename' => PATH_CACHE.'/cfc_data',
    'df_prefix' => 'mc_df_',
    'memcache' => array(
        'time_out' => 1,
        'host' => array( 'memcache://127.0.0.1:11211' )//这个不改好无法充值
        //'host' => array( 'memcache://192.168.5.211:11211' )
    )
);

//MySql配置
//slave数据库从库可以使用多个
/*注意：在作ucenter同步登陆时进行了手写的数据库操作,更改此Mysql配置时应该同时更改web/bbs/uc_client/client.php*/
$GLOBALS['config']['db'] = array(
    'master' => array(
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'user'    => 'root',
        'pass'    => '111111',
        'name'    => 'webgame3',
        'charset' => 'utf-8',
    ),
    'slave'  => array(
        array(
            'host'    => '127.0.0.1',
            'port'    => 3306,
            'user'    => 'root',
            'pass'    => '111111',
            'name'    => 'webgame3',
            'charset' => 'utf-8',
        ),
    /**
     * 如果需要从库的话打开下面的注释，按示例可添加多个从库
     *
        array(
            'host'    => '127.0.0.1',
            'port'    => 3306,
            'user'    => 'root',
            'pass'    => '111111',
            'name'    => 'webgame3',
            'charset' => 'utf-8',
        ),
     */
    ),
);
//session
$GLOBALS['config']['session'] = array(
   'live_time' => 86400,
);

//默认时区
$GLOBALS['config']['timezone_set'] = 'Asia/Shanghai';

// url重写是否开启(本版仅在<{rewrite}><{/rewrite}>中使用rewrite替换有效)
// 此项需要修改 PATH_DATA/rewrite.ini
$GLOBALS['config']['use_rewrite'] = true;

//指示替换网址是在编译前还是输出前，0--前者性能好，1--后者替换更彻底(此项本版没意义)
$GLOBALS['config']['rewrite_rptype'] = 0;

//cookie加密码
$GLOBALS['config']['cookie_pwd'] = '&uop_Ysd@erw!tr';

//默认上传目录
$GLOBALS['config']['upload_dir'] = '/static/uploads';
