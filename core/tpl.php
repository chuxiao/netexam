<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * 模板引擎实现类
 *
 * @author itprato<2500875@qq>
 * @version $Id$
 */
class tpl
{
    protected static $instance = null;
    public static $debug_error = '';

    /**
     * Smarty 初始化
     * @return resource
     */
    public static function init ()
    {
        if (self::$instance === null)
        {
            require_once PATH_LIBRARY . '/smarty/libs/Smarty.class.php';
            self::$instance = new Smarty();
            self::$instance->template_dir = util::path_exists(PATH_ROOT . '/templates/');
            self::$instance->compile_dir = util::path_exists(PATH_ROOT . '/templates_c/');
            self::$instance->left_delimiter = '<{';
            self::$instance->right_delimiter = '}>';
            self::$instance->caching = false;
            self::$instance->compile_check = true;
            self::config();
        }
        return self::$instance;
    }

    protected static function config ()
    {
        $instance->assign('URL_STATIC', URL.'/static');
        $instance->assign('URL', URL);
        //config文件配置
        $instance->assign('title', TITLE);
        $instance->assign('website', WEBSITE);
        $instance->assign('keywords', KEYWORDS);
        $instance->assign('description', DESCRIPTION);
    }

    public static function assign ($tpl_var, $value)
    {
        $instance = self::init();
        $instance->assign($tpl_var, $value);
    }

    public static function display ($tpl, $is_debug_mt=true)
    {
        $instance = self::init();
        $instance->display($tpl);
        if( $is_debug_mt && PHP_SAPI !== 'cli' ) {
            debug_hanlde_xhprof();
        }
    }
}
