<?php
    // MYAPI COOKIE
    define('MYAPI_COOKIE', "OOFA");                                 // COOKIE 名称
    define('MYAPI_COOKIE_SESSION', 'OOFB');                         // COOKIE 名称
    define('MYAPI_COOKIE_ONLINE',"OOFO");                           // 在线COOKIE标志
    define('MYAPI_COOKIE_LAST_LOGIN', "OOFL");                      // 上次登录的账号
    define('MYAPI_COOKIE_CAPTCHA', "captcha");
    define('MYAPI_COOKIE_ACCOUNT_CODE', "acaptcha");
    define('MYAPI_ONLINE_REPORT',true);                             // 是否向MY汇报在线情况
    define("MYAPI_ONLINE_INTERVAL",900);                            // 在线时间间隔 15分钟,15分钟向my汇报一次在线情况
    define('MYAPI_COOKIE_DOMAIN',  ".netexam.com");                     // COOKIE 域名.115.com
    define('MYAPI_COOKIE_EXPIRE',1209600);                         // 自己设定的cookie 14天
    define('MYAPI_COOKIE_ACCOUNT_CODE_EXPIRE', 60);

    // MYAPI签名,加密KEY
    define('MYAPI_SIGN_KEY', 'ACS567DADDCGLP82JG');               // COOKIE签名KEY
    define('MYAPI_ENCRYPT_KEY', 'XDzmcx9283azklZCVSDWEl');          // COOKIE加密KEY


    // API 缓存设置
    define("MYAPI_MEMCACHE",true);                                  // 是否使用缓存,需要有cache::get方法
    define("MYAPI_USERINFO_PREFIX","my_userinfo");            // 用户资料缓存前缀
    define("MYAPI_NOTICE_PREFIX","notice_total");                   // 用户通知(分类)总数
    define("MYAPI_MESSAGE_PREFIX","inbox_total");                   // 用户收件箱(分类)总数
?>
