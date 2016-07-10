<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * 管理菜单读取
 *
 * @version $Id$
 */
class pub_mod_common
{
    public static function set_links()
    {
        $sql = "SELECT * FROM `links`";
        $rsid = db::query($sql);
        $data = db::fetch_all($rsid);
        tpl::assign('links', $data);
    }

    public static function get_back_url($goto, $is_decode = true)
    {
        if (empty($goto))
        {
            /* 如果没有指定回调地址，根据HTTP_REFERER识别 */
            $goto = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
            //$goto = "/?ct=account";
        }

        /* 自动解密链接 */
        if (preg_match("/%2F/", $goto) && $is_decode === true)
        {
            $goto = urldecode($goto);
        }
        return $goto;
    }

    /**
     * 根据item_id得到分表名称
     *
     * @param int $item_value
     * @param string $table
     * @param boolean $is_sphinx
     * @return void
     */
    public static function get_split_table($item_value, $table)
    {
        switch ($table)
        {
            case 'user_details':
                //100张表改为8张表
                //$item_value = str_pad(substr($item_value, -2), 2, "0", STR_PAD_LEFT);
                $item_value = str_pad(substr($item_value, -2) % 8, 2, "0", STR_PAD_LEFT);
                return 'user_details_' . $item_value;
                break;
            case 'user':
                //$item_value 为 $user_id
                //100张表改为8张表 user_00 user_01 user_02 user_03 user_04
                //$item_value = str_pad(substr($item_value, -2), 2, "0", STR_PAD_LEFT);
                $item_value = str_pad(substr($item_value, -2) % 8, 2, "0", STR_PAD_LEFT);
                return 'user_' . $item_value;
                break;
            case 'user_answer':
                //$item_value 为 $user_id
                //100张表改为8张表 user_00 user_01 user_02 user_03 user_04
                //$item_value = str_pad(substr($item_value, -2), 2, "0", STR_PAD_LEFT);
                $item_value = str_pad(substr($item_value, -2) % 8, 2, "0", STR_PAD_LEFT);
                return 'user_answer_' . $item_value;
                break;
            case 'user_exam':
                //$item_value 为 $user_id
                //100张表改为8张表 user_00 user_01 user_02 user_03 user_04
                //$item_value = str_pad(substr($item_value, -2), 2, "0", STR_PAD_LEFT);
                $item_value = str_pad(substr($item_value, -2) % 8, 2, "0", STR_PAD_LEFT);
                return 'user_exam_' . $item_value;
                break;
            default:
                echo "<pre>";
                echo "Table not exist\n";
                debug_print_backtrace();
                exit();
                break;
        }
    }
}
