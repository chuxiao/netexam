<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * 数据库操作类 <<读写分离>>
 *
 * 读 - mysql master
 *    - mysql slave 1
 *    - mysql slave 2
 *    ......
 *
 * 写 - master
 *
 * @author itprato<2500875@qq>
 * @version $Id$
 */
class db
{

    //连接默认是 $links[ self::$link_name ]['w'] ||  $links[ self::$link_name ]['r']
    //如果用户要开一个新的连接, 用 set_connect($link_name, db配置) ， 当前链接sql操作完后，使用 set_connect_default 还原为默认
    //config 格式与 $GLOBALS['config']['db'] 一致
    private static $links = array();

    //数据库配置数组
    private static $configs = array();

    //当前连接名，系统通过 $links[ self::$link_name ]['w'] ||  $links[ self::$link_name ]['r'] 识别特定配置的链接
    private static $link_name = 'default';

    //当前使用的链接， 如果不用 set_connect 或 set_connect_default 进行改变， 这连接由系统决定
    private static $cur_link = null;

    private static $query_count = 0;
    private static $log_slow_query = true;
    private static $log_slow_time = 0.05;

    //游标集
    private static $cur_result = null;
    private static $results = array();

    //是否对SQL语句进行安全检查并处理
    public static $safe_test = true;
    public static $rps = array('/*', '--', '#', 'union', 'sleep', 'benchmark', 'load_file', 'outfile');
    public static $rpt = array('/×', '——', '＃', 'ｕｎｉｏｎ', 'ｓｌｅｅｐ', 'ｂｅｎｃｈｍａｒｋ', 'ｌｏａｄ_ｆｉｌｅ', 'ｏｕｔｆｉｌｅ');

    /**
     * 改变链接为指定配置的链接(如果不同时使用多个数据库，不会涉及这个操作)
     * @parem  $link_name 链接标识名
     * @parem  $config 多次使用时， 这个数组只需传递一次
     *         config 格式与 $GLOBALS['config']['db'] 一致
     * @return void
     */
    public static function set_connect($link_name, $config = array() )
    {
        self::$link_name = $link_name;
        if( !empty($config) ) {
            self::$configs[self::$link_name]   = $config;
        } else {
            if( empty(self::$configs[self::$link_name]) ) {
                throw new Exception( "You not set a config array for connect!" );
            }
        }
    }

    /**
     * 还原为默认连接(如果不同时使用多个数据库，不会涉及这个操作)
     * @parem $config 指定配置（默认使用inc_config.php的配置）
     * @return void
     */
    public static function set_connect_default( $config = '' )
    {
        if( empty($config) ) {
            $config = self::_get_default_config();
        }
        self::set_connect('default', $config );
    }

    /**
     * 获取默认配置
     */
    private static function _get_default_config()
    {
        if( empty(self::$configs['default']) )
        {
            if( !is_array($GLOBALS['config']['db']) ) {
                handler_fatal_error( 'db.php _get_default_config()', '没有mysql配置的情况下，尝试使用数据库，page: '.util::get_cururl() );
            }
            self::$configs['default'] = $GLOBALS['config']['db'];
        }
        return self::$configs['default'];
    }

    /**
     * (读+写)连接数据库+选择数据库
     * @parem $is_master 是否为主库
     * @return void
     */
    private static function _init_mysql( $is_master = false )
    {
        //获取配置
        $db_config = (self::$link_name=='default' ? self::_get_default_config() : self::$configs[self::$link_name]);
        //连接属性及host
        if( $is_master === true )
        {
            $link = 'r';
            $key = array_rand($db_config['slave']);
            $db_profile = $db_config['slave'][$key];
        }
        else
        {
            $link = 'w';
            $db_profile = $GLOBALS['config']['db']['master'];
        }
        //创建连接
        if( empty( self::$links[self::$link_name][$link] ) )
        {
            try
            {
                $mysqli = new mysqli($db_profile['host'], $db_profile['user'], $db_profile['pass'], $db_profile['name'], $db_profile['port']);
                if( $mysqli->connect_errno )
                {
                    throw new Exception($mysqli->connect_error);
                }
                else
                {
                    $charset = str_replace('-', '', strtolower($db_profile['charset']));
                    $mysqli->query(" SET character_set_connection=" . $charset . ", character_set_results=" . $charset . ", character_set_client=binary, sql_mode='' ");
                    self::$links[self::$link_name][$link] = $mysqli;
                }
            }
            catch (Exception $e)
            {
                handler_fatal_error( 'db.php _init_mysql()', $e->getMessage().' page: '.util::get_cururl() );
            }
        }
        self::$cur_link = self::$links[self::$link_name][$link];
        return self::$links[self::$link_name][$link];
    }

    /**
     * 返回查询游标
     * @return rsid
     */
    private static function _get_rsid( $rsid='' )
    {
        //return $rsid=='' ? self::$cur_result : self::$results[(int)$rsid];
        return self::$cur_result;
    }

    /**
     * 执行一条语句(读 + 写)
     *
     * @param  string $sql
     * @return $rsid (返回一个游标id或false)
     */
    public static function query ($sql, $is_master = false)
    {
        $start_time = microtime(true);
        $sql = trim($sql);

        //对SQL语句进行安全过滤
        if( self::$safe_test==true ) {
            $sql = self::_filter_sql($sql);
        }

        //获取当前连接
        if( $is_master===true )
        {
            self::$cur_link = self::_init_mysql( false );
        }
        else
        {
            if( substr(strtolower($sql), 0, 1) === 's' )
            {
                self::$cur_link = self::_init_mysql( false );
            } else {
                self::$cur_link = self::_init_mysql( true );
            }
        }

        try
        {
            file_put_contents('/tmp/sql.txt', $sql . "\n", FILE_APPEND);
            self::$cur_result = self::$cur_link->query($sql);
            //记录慢查询
            if( self::$log_slow_query )
            {
                $querytime = microtime(true) - $start_time;
                if( $querytime > self::$log_slow_time )
                {
                    self::_slow_query_log($sql, $querytime);
                }
            }
            if (self::$cur_result === false)
            {
                throw new Exception("Mysql query error: ".$sql);
                return false;
            }
            else
            {
                self::$query_count ++;
                return self::$cur_result;
            }
        }
        catch (Exception $e)
        {
            handler_fatal_error( 'db.php query()', $e->getMessage().'|'.$sql.' page:'.util::get_cururl() );
        }
    }

    /**
     * (写)，执行一个出错也不中断的语句（通常是涉及唯一主键的操作）
     * @param  string $sql
     * @return bool
     */
    public static function query_over( $sql )
    {
        self::$cur_link = self::_init_mysql(false, true);
        if( self::$safe_test==true )
        {
            $sql = self::_filter_sql($sql);
        }
        $rs = @self::$cur_link->query($sql);
        return $rs;
    }

    /**
     * 取得最后一次插入记录的ID值
     *
     * @return int
     */
    public static function insert_id ()
    {
        return self::$cur_link->insert_id;
    }

    /**
     * 返回受影响数目
     * @return init
     */
    public static function affected_rows ()
    {
        return self::$cur_link->affected_rows;
    }

    /**
     * 返回本次查询所得的总记录数...
     *
     * @return int
     */
    public static function num_rows ( $rsid='' )
    {
        $rsid = self::_get_rsid( $rsid );
        return $rsid->num_rows;
    }

    /**
     * (读)返回单条记录数据
     *
     * @parem  $rsid   (查询语句返回的游标，如果此项为空， 则用最后一次查询的游标)
     * @param  $result_type (MYSQL_ASSOC==1 MYSQL_NUM==2 MYSQL_BOTH==3)
     * @return array
     */
    public static function fetch_one($rsid = '')
    {
        $rsid = self::_get_rsid( $rsid );
        $row = $rsid->fetch_assoc();
        return $row;
    }

    /**
     * (读)直接返回单条记录数据
     *
     * @deprecated   MYSQL_ASSOC==1 MYSQL_NUM==2 MYSQL_BOTH==3
     * @param  int   $result_type
     * @return array
     */
    public static function get_one ($sql)
    {
        if( !preg_match("/limit/i", $sql) ) {
            $sql = preg_replace("/[,;]$/i", '', trim($sql))." limit 1 ";
        }
        $cur_rsid = self::$cur_result;
        $rsid = self::query($sql, false);
        $row = $rsid->fetch_assoc();

        //使cur的查询游标还原为get_one前
        if( !empty($cur_rsid) ) {
            self::$cur_result = $cur_rsid;
        }

        return $row;
    }

    /**
     * (读)返回多条记录数据
     *
     * @deprecated    MYSQL_ASSOC==1 MYSQL_NUM==2 MYSQL_BOTH==3
     * @param   int   $result_type
     * @return  array
     */
    public static function fetch_all ( $rsid='' )
    {
        $rsid = self::_get_rsid( $rsid );
        $row = $rows = array();
        while ($row = $rsid->fetch_assoc())
        {
            $rows[] = $row;
        }
        return empty($rows) ? false : $rows;
    }

    /**
     * SQL语句过滤程序（检查到有不安全的语句仅作替换和记录攻击日志而不中断）
     * @parem string $sql 要过滤的SQL语句 
     */
    private static function _filter_sql($sql)
    {
        $clean = $error='';
        $old_pos = 0;
        $pos = -1;
        $userIP = util::get_client_ip();
        $getUrl = util::get_cururl();
        //完整的SQL检查
        while (true)
        {
            $pos = strpos($sql, '\'', $pos + 1);
            if ($pos === false)
            {
                break;
            }
            $clean .= substr($sql, $old_pos, $pos - $old_pos);
            while (true)
            {
                $pos1 = strpos($sql, '\'', $pos + 1);
                $pos2 = strpos($sql, '\\', $pos + 1);
                if ($pos1 === false)
                {
                    break;
                }
                elseif ($pos2 == false || $pos2 > $pos1)
                {
                    $pos = $pos1;
                    break;
                }
                $pos = $pos2 + 1;
            }
            $clean .= '$s$';
            $old_pos = $pos + 1;
        }
        $clean .= substr($sql, $old_pos);
        $clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));
        $fail = false;
        //sql语句中出现注解
        if (strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, '#') !== false)
        {
            $fail = true;
            $error = 'commet detect';
        }
        //常用的程序里也不使用union，但是一些黑客使用它，所以检查它
        else if (strpos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0)
        {
            $fail = true;
            $error = 'union detect';
        }
        //这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库
        elseif (strpos($clean, 'sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0)
        {
            $fail = true;
            $error = 'slown down detect';
        }
        elseif (strpos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0)
        {
            $fail = true;
            $error="slown down detect";
        }
        elseif (strpos($clean, 'load_file') !== false && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0)
        {
            $fail = true;
            $error="file fun detect";
        }
        elseif (strpos($clean, 'into outfile') !== false && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0)
        {
            $fail = true;
            $error="file fun detect";
        }
        //检测到有错误后记录日志并对非法关键字进行替换
        if ( $fail===true )
        {
            $sql = str_ireplace(self::$rps, self::$rpt, $sql);

            //进行日志
            //$gurl = htmlspecialchars( util::get_cururl() );
            //$msg  = "Time: {$qtime} -- ".date('y-m-d H:i', time())." -- {$gurl}<br>\n".htmlspecialchars( $sql )."<hr size='1' />\n";
            //log::add('filter_sql', $msg);

            return $sql;
        }
        else
        {
            return $sql;
        }
    }

    /**
     * 修正被防注入程序修改了的字符串
     * 在读出取时有必要完全还原才使用此方法
     * @param string $fvalue
     */
    public static function revert($fvalue)
    {
        $fvalue = str_ireplace(self::$rpt, self::$rps, $fvalue);
        return $fvalue;
    }

    /**
     * 记录慢查询日志
     *
     * @param string $sql
     * @param float $qtime
     * @return bool
     */
    private static function _slow_query_log($sql, $qtime)
    {
        $gurl = htmlspecialchars( util::get_cururl() );
        $msg  = "Time: {$qtime} -- ".date('y-m-d H:i', time())." -- {$gurl}<br>\n".htmlspecialchars( $sql )."<hr size='1' />\n";
        log::add('slow_query', $msg);
    }

    /**
     * 设置是否自动提交事务
     * 只针对InnoDB类型表
     * 
     * @access public
     * @param bool $mode
     * @return bool
     */
    public static function autocommit( $mode = false )
    {
        self::$cur_link = self::_init_mysql( true );
        $int = $mode ? 1 : 0;
        return @self::$cur_link->query("SET autocommit={$int}");
    }

    /**
     * 提交事务
     * 在执行self::autocommit||begin_tran后执行
     * 
     * @access public
     * @return bool
     */
    public static function commit()
    {
        return @self::$cur_link->query('COMMIT');
    }

    /**
     * 回滚事务
     * 在执行self::autocommit||begin_tran后执行后执行
     * 
     * @access public
     * @return bool
     */
    public static function rollback()
    {
        return @self::$cur_link->query('ROLLBACK');
    }

    //### 以下为二次开发的代码 ################################################
    /**
     * 以新的$key_values更新mysql数据,
     *
     * 注意:该方法不检查key_falues的数据正确性,不支持诸如UNIX_TIMESTAMP()等mysql方法
     *
     * @param array $key_values array('aid'=>1,'cid'=>2)
     * @param string $table_name  e.g. u_user_file_002
     * @param string or array $where e.g. $where[] = "`file_id` = '10024'";$where[] = "`user_id` = '122332'";
     * @return boolean 如果想得到affected_rows请调用 db::affected_rows
     */
    public static function update($key_values, $table_name, $where)
    {
        $sql = "UPDATE `{$table_name}` SET ";

        foreach ($key_values as $k => $v)
        {
            $sql .= "`{$k}` = '{$v}',";
        }
        if ($where)
        {
            if (is_array($where)) 
            {
                $where_sql = implode(' AND ', $where);
            }
            else
            {
                $where_sql = "and {$where}";
            }
        }
        $sql = substr($sql, 0, -1) . "  WHERE 1 {$where_sql}";

        return self::query($sql);
    }

    /**
     * 插入一条新的数据
     * 注意:该方法不检查key_falues的数据正确性,不支持诸如UNIX_TIMESTAMP()等mysql方法
     * 如果想得到affected_rows请调用cls_database::insert_id()
     * @param array $key_values array('aid'=>1,'cid'=>2)
     * @param string $where e.g. `file_id` = 10024 AND `user_id` = 122332
     * @param <type> $table_name  e.g. u_user_file_002
     * @return boolean 如果想得到insert_id请调用cls_database::insert_id()
     */
    public static function insert($key_values, $table_name)
    {
        $items_sql  = $values_sql = "";
        foreach ($key_values as $k => $v)
        {
            $items_sql .= "`$k`,";
            $values_sql .= "'$v',";
        }
        $sql = "INSERT INTO {$table_name} (" . substr($items_sql, 0, -1) . ") VALUES (" . substr($values_sql, 0, -1) . ")";
        return self::query($sql);
    }

    /**
     * 取得一个表的初始数组,包括所有表字段及默认值，无默认值为''
     * @param string $table_name
     * @return array $result 表结构数组
     */
    public static function get_structure($table_name)
    {
        $rt     = self::get_all("DESC `{$table_name}`");
        $result = array();
        foreach ($rt as $k => $v)
        {
            $result[$v['Field']] = $v['Default'] === NULL ? '' : $v['Default'];
        }
        return $result;
    }

    /**
     * 根据SQL语句获取数据表名称
     *
     * @param string $sql
     * @return array
     */
    public static function get_table_name($sql)
    {
        preg_match('/(' .
            '\bfrom\s+[\`]?(?<from>[a-zA-Z\._\d]+)[\`]?\b' . '|' .
            '\bupdate\s+[\`]?(?<update>([a-zA-Z\._\d]+))[\`]?\b' . '|' .
            '\binsert\s+(?:\binto\b)?\s+[\`]?(?<insert>[a-zA-Z\._\d]+)[\`]?\b' . '|' .
            '\bdelete\s+(?:\bfrom\b)?\s+[\`]?(?<delete>[a-zA-Z\._\d]+)[\`]?\b' . '|' .
            '\btruncate\s+table\s+[\`]?(?<truncate>[a-zA-Z\._\d]+)[\`]?\b' . '|' .
            '\bjoin\s+[\`]?(?<join>[a-zA-Z\._\d]+)[\`]?\b' .
            ')/i', $sql, $source);
        if ($source)
        {
            foreach ($source as $k => $v)
            {
                if (!empty($v) && in_array($k, array("from", "update", "insert", "truncate", "join", "delete"), true))
                {
                    $table_name = $v;
                    break;
                }
            }

            /* 没有捕获到数据表 */
            if (empty($table_name))
            {
                //   set_mq("DATABASE_MATCH", array("sql" => $sql, "source" => $source));
            }


            if (preg_match("/\_(?<index>\d+)/", $table_name, $index))
            {
                $index             = $index['index'];
                $table_name_source = str_replace("_" . $index, '', $table_name);
            }
            else
            {
                $index             = false;
                $table_name_source = $table_name;
            }
            return array("table_name" => $table_name, "table_name_source" => $table_name_source, "index" => $index);
        }
        return false;
    }
}
