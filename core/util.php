<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * 实用函数集合
 *
 * 替代lib_common
 *
 * @author itprato<445307582@qq>
 * @version $Id$
 */
class util
{
    public static $client_ip = null;

    public static $cfc_handle = null;

    /**
     * 获得用户的真实IP 地址
     *
     * @param 多个用多行分开
     * @return void
     */
    public static function get_client_ip()
    {
        static $realip = NULL;
        if( self::$client_ip !== NULL )
        {
            return self::$client_ip;
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR2']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR2']);
            foreach ($arr as $ip)
            {
                $ip = trim($ip);
                if ($ip != 'unknown')
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr as $ip)
            {
                $ip = trim($ip);
                if ($ip != 'unknown' )
                {
                    $realip = $ip;
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        self::$client_ip = ! empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return self::$client_ip;
    }

    /**
     * 写文件
     */
    function put_file($file, $content, $flag = 0)
    {
        $pathinfo = pathinfo ( $file );
        if (! empty ( $pathinfo ['dirname'] ))
        {
            if (file_exists ( $pathinfo ['dirname'] ) === false)
            {
                if (@mkdir ( $pathinfo ['dirname'], 0777, true ) === false)
                {
                    return false;
                }
            }
        }
        if ($flag === FILE_APPEND)
        {
            return @file_put_contents ( $file, $content, FILE_APPEND );
        }
        else
        {
            return @file_put_contents ( $file, $content, LOCK_EX );
        }
    }

    /**
     * 获得当前的Url
     */
    public static function get_cururl()
    {
        if(!empty($_SERVER["REQUEST_URI"]))
        {
            $scriptName = $_SERVER["REQUEST_URI"];
            $nowurl = $scriptName;
        }
        else
        {
            $scriptName = $_SERVER["PHP_SELF"];
            $nowurl = empty($_SERVER["QUERY_STRING"]) ? $scriptName : $scriptName."?".$_SERVER["QUERY_STRING"];
        }
        return $nowurl;
    }

    /**
     * 检查路径是否存在
     * @parem $path
     * @return bool
     */
    public static function path_exists( $path )
    {
        $pathinfo = pathinfo ( $path . '/tmp.txt' );
        if ( !empty( $pathinfo ['dirname'] ) )
        {
            if (file_exists ( $pathinfo ['dirname'] ) === false)
            {
                if (mkdir ( $pathinfo ['dirname'], 0777, true ) === false)
                {
                    return false;
                }
            }
        }
        return $path;
    }

    /**
     * 判断是否为utf8字符串
     * @parem $str
     * @return bool
     */
    public static function is_utf8($str)
    {
        if ($str === mb_convert_encoding(mb_convert_encoding($str, "UTF-32", "UTF-8"), "UTF-8", "UTF-32"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 公共分页函数
     *
     *  @param array $config
     *               $config['current_page']   //当前页数
     *               $config['page_size']      //每页显示多少条
     *               $config['total_rs']       //总记录数
     *               $config['url_prefix']     //网址前缀
     *               $config['page_name']      //当前分页变量名(默认是page_no， 即访问是用 url_prefix&page_no=xxx )
     *               $config['move_size']      //前后偏移量（默认是5）
     *               $config['input']          //是否使用输入跳转框(0|1)
     * 输出格式：
     * <div class="page">
     * <span class="nextprev">&laquo; 上一页</span>
     * <span class="current">1</span>
     * <a href="">2</a>
     *  <a href="" class="nextprev">下一页 &raquo;</a>
     *  <span>共 100 页</span>
     * </div>
     *
     * @return string
     */
    public static function pagination ( $config )
    {
        //参数处理
        $url_prefix    = empty($config['url_prefix']) ? '' : $config['url_prefix'];
        $current_page  = empty($config['current_page']) ? 1 : intval($config['current_page']);
        $page_name     = empty($config['page_name']) ? 'page_no' : $config['page_name'];
        $page_size     = empty($config['page_size']) ? 0 : intval($config['page_size']);
        $total_rs      = empty($config['total_rs']) ? 0 : intval($config['total_rs']);
        $total_page    = ceil($total_rs / $page_size);
        $move_size     = empty($config['move_size']) ? 5 : intval($config['move_size']);

        //总页数不到二页返回空
        if( $total_page < 2 )
        {
            return '';
        }

        //分页内容
        $pages = '<div class="pagenumber">';

        //下一页
        $next_page = $current_page + 1;
        //上一页
        $prev_page = $current_page - 1;
        //末页
        $last_page = $total_page;

        //上一页、首页
        if( $current_page > 1 )
        {
            $pages .= "<a href='{$url_prefix}' class='first-page'>首页</a>\n";
            $pages .= "<a href='{$url_prefix}&{$page_name}={$prev_page}' class='prev'>&laquo;&lt;</a>\n";
        }
        else
        {
            $pages .= "<a class='first-page'>首页</a>\n";
            $pages .= "<a class='prev'>&laquo;&lt;</a>\n";
        }

        //前偏移
        for( $i = $current_page - $move_size; $i < $current_page; $i++ )
        {
            if ($i < 1) {
                continue;
            }
            $pages .= "<a href='{$url_prefix}&{$page_name}={$i}'>$i</a>\n";
        }
        //当前页
        $pages .= "<span class='current'>" . $current_page . "</span>\n";

        //后偏移
        $flag = 0;
        if ( $current_page < $total_page )
        {
            for ($i = $current_page + 1; $i <= $total_page; $i++)
            {
                $pages .= "<a href='{$url_prefix}&{$page_name}={$i}'>$i</a>\n";
                $flag++;
                if ($flag == $move_size)
                {
                    break;
                }
            }
        }

        //下一页、末页
        if( $current_page != $total_page )
        {
            $pages .= "<a href='{$url_prefix}&{$page_name}={$next_page}' class='next'>&gt;&raquo;</a>\n";
            $pages .= "<a href='{$url_prefix}&{$page_name}={$last_page}' class='last-page'>末页</a>\n";
        }
        else
        {
            $pages .= "<a class='next'>&gt;&raquo;</a>\n";
            $pages .= "<a class='last-page'>末页</a>\n";
        }

        //增加输入框跳转
        if( !empty($config['input']) )
        {
            $pages .= '<input type="text" class="page" onkeydown="javascript:if(event.keyCode==13){ location=\''.$url_prefix.'&'.$page_name.'=\'+this.value; }" onkeyup="value=value.replace(/[^\d]/g,\'\')" />';
        }

        $pages .= "<span>共 {$total_page} 页 / {$total_rs} 条记录</span>\n";
        $pages .= '</div>';

        return $pages;
    }

    /**
     * utf8编码模式的中文截取2，单字节截取模式
     * 这里不使用mbstring扩展
     * @return string
     */
    public static function utf8_substr($str, $slen, $startdd=0)
    {
        return mb_substr($str , $startdd , $slen , 'UTF-8');
    }

    /**
     * 从普通时间返回Linux时间截(strtotime中文处理版)
     * @parem string $dtime
     * @return int
     */
    public static function cn_strtotime( $dtime )
    {
        if(!preg_match("/[^0-9]/", $dtime))
        {
            return $dtime;
        }
        $dtime = trim($dtime);
        $dt = Array(1970, 1, 1, 0, 0, 0);
        $dtime = preg_replace("/[\r\n\t]|日|秒/", " ", $dtime);
        $dtime = str_replace("年", "-", $dtime);
        $dtime = str_replace("月", "-", $dtime);
        $dtime = str_replace("时", ":", $dtime);
        $dtime = str_replace("分", ":", $dtime);
        $dtime = trim(preg_replace("/[ ]{1,}/", " ", $dtime));
        $ds = explode(" ", $dtime);
        $ymd = explode("-", $ds[0]);
        if(!isset($ymd[1]))
        {
            $ymd = explode(".", $ds[0]);
        }
        if(isset($ymd[0]))
        {
            $dt[0] = $ymd[0];
        }
        if(isset($ymd[1])) $dt[1] = $ymd[1];
        if(isset($ymd[2])) $dt[2] = $ymd[2];
        if(strlen($dt[0])==2) $dt[0] = '20'.$dt[0];
        if(isset($ds[1]))
        {
            $hms = explode(":", $ds[1]);
            if(isset($hms[0])) $dt[3] = $hms[0];
            if(isset($hms[1])) $dt[4] = $hms[1];
            if(isset($hms[2])) $dt[5] = $hms[2];
        }
        foreach($dt as $k=>$v)
        {
            $v = preg_replace("/^0{1,}/", '', trim($v));
            if($v=='')
            {
                $dt[$k] = 0;
            }
        }
        $mt = mktime($dt[3], $dt[4], $dt[5], $dt[1], $dt[2], $dt[0]);
        if(!empty($mt))
        {
            return $mt;
        }
        else
        {
            return strtotime( $dtime );
        }
    }

    /**
     * 发送邮件
     * @param array  $to      收件人
     * @param string $subject 邮件标题
     * @param string $body　      邮件内容
     * @return bool
     * @author xiaocai
     */
    public static function send_email($to, $subject, $body)
    {
        $send_account = $GLOBALS['config']['send_smtp_mail_account'];
        try
        {
            $smtp = new cls_mail($send_account['host'], $send_account['port'], true, $send_account['user'], $send_account['password']);
            $smtp->debug = $send_account['debug'];
            $result = $smtp->sendmail($to, $send_account['from'], $subject, $body, $send_account['type']);

            return $result;
        }
        catch( Exception $e )
        {
            return false;
        }
    }

    //将中文进行 urlencode 转换
    public static function q_encode($str)
    {
        $data_code = "";
        $data = array_filter(explode(" ",$str));
        $data = array_flip(array_flip($data));
        foreach ($data as $ss) {
            if (strlen($ss)>1 )
                $data_code .= str_replace("%","",urlencode($ss)) . " ";
        }
        $data_code = trim($data_code);
        return $data_code;
    }


    public static function get_domain($url){
        $pattern = "/[\w-]+\.(com|net|org|gov|cc|biz|info|cn)(\.(cn|hk))*/";
        preg_match($pattern, $url, $matches);
        if(count($matches) > 0) {
            return $matches[0];
        }else{
            $rs = parse_url($url);
            $main_url = $rs["host"];
            if(!strcmp(long2ip(sprintf("%u",ip2long($main_url))),$main_url)) {
                return $main_url;
            }else{
                $arr = explode(".",$main_url);
                $count=count($arr);
                $endArr = array("com","net","org","3322");//com.cn  net.cn 等情况
                if (in_array($arr[$count-2],$endArr)){
                    $domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];
                }else{
                    $domain =  $arr[$count-2].".".$arr[$count-1];
                }
                return $domain;
            }// end if(!strcmp...)
        }// end if(count...)
    }// end function

    /**
     * 根据日期得到年龄
     * @param string $birthday Y-m-d
     * @return <type>
     */
    function get_age($birthday)
    {
        if(!$birthday) return "-";
        $by = substr($birthday, 0,4);
        $bm = substr($birthday, 4,2);
        $bd = substr($birthday, 6,2);

        list($y, $m, $d) = explode("-", date('Y-m-d'));
        if(($m > $bm) || ($m == $bm && $d > $bd))
            $y++;
        return $y-$by;
    }

    /**
     *getConstellation 根据出生生日取得星座
     *
     *@param String $brithday 用于得到星座的日期 格式为yyyy-mm-dd
     *
     *@param Array $format 用于返回星座的名称
     *
     *@return String
     */
    public static function get_constellation($birthday, $format=null)
    {
        $pattern = '/^\d{4}-\d{1,2}-\d{1,2}$/';
        if (!preg_match($pattern, $birthday, $matchs))
        {
            return null;
        }
        $date = explode('-', $birthday);
        $year = $date[0];
        $month = $date[1];
        $day   = $date[2];
        if ($month <1 || $month>12 || $day < 1 || $day >31)
        {
            return null;
        }
        //设定星座数组
        $constellations = array(
            '摩羯座', '水瓶座', '双鱼座', '白羊座', '金牛座', '双子座',
            '巨蟹座','狮子座', '处女座', '天秤座', '天蝎座', '射手座',);

        //或
        /*$constellations = array(
            'Capricorn', 'Aquarius', 'Pisces', 'Aries', 'Taurus', 'Gemini',
        'Cancer','Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius',);*/

        //设定星座结束日期的数组，用于判断
        $enddays = array(19, 18, 20, 20, 20, 21, 22, 22, 22, 22, 21, 21,);
        //如果参数format被设置，则返回值采用format提供的数组，否则使用默认的数组
        if ($format != null)
        {
            $values = $format;
        }
        else
        {
            $values = $constellations;
        }
        //根据月份和日期判断星座
        switch ($month)
        {
        case 1:
            if ($day <= $enddays[0])
            {
                $constellation = $values[0];
            }
            else
            {
                $constellation = $values[1];
            }
            break;
        case 2:
            if ($day <= $enddays[1])
            {
                $constellation = $values[1];
            }
            else
            {
                $constellation = $values[2];
            }
            break;
        case 3:
            if ($day <= $enddays[2])
            {
                $constellation = $values[2];
            }
            else
            {
                $constellation = $values[3];
            }
            break;
        case 4:
            if ($day <= $enddays[3])
            {
                $constellation = $values[3];
            }
            else
            {
                $constellation = $values[4];
            }
            break;
        case 5:
            if ($day <= $enddays[4])
            {
                $constellation = $values[4];
            }
            else
            {
                $constellation = $values[5];
            }
            break;
        case 6:
            if ($day <= $enddays[5])
            {
                $constellation = $values[5];
            }
            else
            {
                $constellation = $values[6];
            }
            break;
        case 7:
            if ($day <= $enddays[6])
            {
                $constellation = $values[6];
            }
            else
            {
                $constellation = $values[7];
            }
            break;
        case 8:
            if ($day <= $enddays[7])
            {
                $constellation = $values[7];
            }
            else
            {
                $constellation = $values[8];
            }
            break;
        case 9:
            if ($day <= $enddays[8])
            {
                $constellation = $values[8];
            }
            else
            {
                $constellation = $values[9];
            }
            break;
        case 10:
            if ($day <= $enddays[9])
            {
                $constellation = $values[9];
            }
            else
            {
                $constellation = $values[10];
            }
            break;
        case 11:
            if ($day <= $enddays[10])
            {
                $constellation = $values[10];
            }
            else
            {
                $constellation = $values[11];
            }
            break;
        case 12:
            if ($day <= $enddays[11])
            {
                $constellation = $values[11];
            }
            else
            {
                $constellation = $values[0];
            }
            break;
        }
        return $constellation;
    }
    /**
     * 0大于18岁，1小于18岁
     * gt18,判断是否大于18岁
     */
    public static function gt18($user_id)
    {
        $user_info = pub_mod_user::get_one_user_details($user_id);
        $reg_time = $user_info['reg_time'];  //如果2012.11.20以前用户不在追究验证
        $time = mktime(0,0,0,11,20,2012);
        $IDCard = $user_info['idcard'];
        if($reg_time<$time)
        {
            return 0;exit;  //大于18
        }elseif(empty($IDCard)){
            return 1;exit;  //小于18
        }else{
            if(!preg_match("/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/",$IDCard) && !preg_match("/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d|x|X)$/",$IDCard)){
                $flag=1;
            }else{
                if(strlen($IDCard)==18){
                    $tyear=intval(substr($IDCard,6,4));
                    $tmonth=intval(substr($IDCard,10,2));
                    $tday=intval(substr($IDCard,12,2));
                    if($tyear>date("Y")||$tyear<(date("Y")-100)){
                        $flag=0;
                    }
                    elseif($tmonth<0||$tmonth>12){
                        $flag=0;
                    }
                    elseif($tday<0||$tday>31){
                        $flag=0;
                    }else{
                        $tdate=$tyear."-".$tmonth."-".$tday." 00:00:00";
                        if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
                            $flag=0;
                        }else{
                            $flag=1;
                        }
                    }
                }elseif(strlen($IDCard)==15){
                    $tyear=intval("19".substr($IDCard,6,2));
                    $tmonth=intval(substr($IDCard,8,2));
                    $tday=intval(substr($IDCard,10,2));
                    if($tyear>date("Y")||$tyear<(date("Y")-100)){
                        $flag=0;
                    }
                    elseif($tmonth<0||$tmonth>12){
                        $flag=0;
                    }
                    elseif($tday<0||$tday>31){
                        $flag=0;
                    }else{
                        $tdate=$tyear."-".$tmonth."-".$tday." 00:00:00";
                        if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){
                            $flag=0;
                        }else{
                            $flag=1;
                        }
                    }
                }
            }
            return $flag;
        }
    }

    /**
     * 在二维数组中找第二维数组中，$key==$value 的
     *
     * @param array $array
     * @param string $item
     * @param string $value
     * @return array or false
     */
    function array_search2($array, $item, $value, $return_key = false)
    {
        if (!is_array($array) || empty($item) || empty($value))
        {
            return false;
        }
        foreach ($array as $k => $v)
        {
            if (isset($v[$item]) && $v[$item] === $value)
            {
                if ($return_key)
                {
                    return $k;
                }
                return $v;
            }
        }
        return false;
    }
    /**
     *
     * 限制ip访问游戏
     */
    function ipallow()
    {
        $ip =  util::get_client_ip();
        $tip = $GLOBALS['config']['ipallow'];
        if(in_array($ip,$tip))
        {
            return true;
        }else{
            echo "<script>alert('未到开服时间')</script>";
            exit();
        }
    }
    /**
     *
     *修改配置文件
     *
     */
    function get_config($file, $ini, $type="string")
    {
        if(!file_exists($file))
        {
            return false;
        }else{
            $str = file_get_contents($file);
            if ($type=="int"){
                $config = preg_match("/".preg_quote($ini)."=(.*);/", $str, $res);
                return $res[1];
            } else{
                $config = preg_match("/".preg_quote($ini)."=\"(.*)\";/", $str, $res);

            }
            return $res[1];
        }
    }
    function update_config($file, $ini, $value,$type="string"){
        if(!file_exists($file))
        {
            return false;
        }else{
            $str = file_get_contents($file);
            $str2="";
            if($type=="int"){
                $str2 = preg_replace("/".preg_quote($ini)."=(.*);/", $ini."=".$value.";",$str);
            }else{
                $str2 = preg_replace("/".preg_quote($ini)."=(.*);/",$ini."=\"".$value."\";",$str);
            }
            //echo preg_quote($ini);exit;
            util::put_file($file, $str2);
        }
    }

    function getExplorer() {

        if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 8.0"))
            return "Explorer";
        else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 7.0"))
            return "Explorer";
        else if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0"))
            return "Explorer";
        else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox/3"))
            return "Firefox";
        else if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox/2"))
            return "Firefox";
        else if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome"))
            return "Chrome";
        else if(strpos($_SERVER["HTTP_USER_AGENT"],"Safari"))
            return "Safari";
        else if(strpos($_SERVER["HTTP_USER_AGENT"],"Opera"))
            return "Opera";
        else return $_SERVER["HTTP_USER_AGENT"];
    }
    function randomkeys($length)
    {
        $pattern='1234567890abcdefhijklmnopqrstuvwxyzABCDEFHIJKLOMNOPQRSTUVWXYZ';
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};    //生成php随机数
        }
        return $key;
    }
}

