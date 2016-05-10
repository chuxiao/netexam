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
    public static $appname = '';
    public static $debug_error = '';

    /**
     * Smarty 初始化
     * @return resource
     */
    public static function init ()
    {
        global $config_appname;
        self::$appname = empty(self::$appname) ? $config_appname : self::$appname;
        if (self::$instance === null)
        {
            require_once PATH_LIBRARY . '/smarty/Smarty.class.php';
            self::$instance = new Smarty();
            self::$instance->template_dir = util::path_exists(PATH_ROOT . '/templates/template/');
            self::$instance->compile_dir = util::path_exists(PATH_ROOT . '/templates/compile/');
            self::$instance->cache_dir = util::path_exists(PATH_ROOT . '/templates/cache/');
            self::$instance->left_delimiter = '<{';
            self::$instance->right_delimiter = '}>';
            self::$instance->caching = false;
            self::$instance->compile_check = true;
            self::$instance->plugins_dir[] = util::path_exists(PATH_LIBRARY . '/smarty_plugins');
            //self::$instance->load_filter ( 'output', 'gzip' );
            //self::$instance->load_filter('output', 'gzip');
            self::config();
        }
        return self::$instance;
    }

    protected static function config ()
    {
        $instance = self::init();
        $instance->assign('URL_STATIC', URL.'/static');
        $instance->assign('URL', URL);
		$instance->assign('url_bbs', URL_BBS);
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
        /*$instance = self::init();
        $app_tpldir = empty(self::$appname) ? '' : self::$appname.'/';
        $instance->display($app_tpldir.$tpl);*/
        $html = self::fetch($tpl);
        if ($GLOBALS['config']['use_rewrite'])
        {
            cls_rewrite::convert_html($html);
        }
        echo $html;
        if( $is_debug_mt && PHP_SAPI !== 'cli' ) {
            debug_hanlde_xhprof();
        }
    }

    public static function fetch($tpl)
    {
        $instance = self::init();
        $app_tpldir = empty(self::$appname) ? '' : self::$appname.'/';
        return $instance->fetch($app_tpldir.$tpl);
    }
}
