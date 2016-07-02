<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * 管理菜单读取
 *
 * @version $Id$
 */
class pub_mod_exam
{
    /**
     * 获取考试相关信息
     */
    public static function get_all_exam_info()
    {
        $sql = "SELECT * FROM EXAM";
        $result = db::query($sql);
        return $result;
    }

    public static function get_one_exam_info($exam_id)
    {
        $sql = "SELECT * FROM EXAM WHERE ID = ".$exam_id;
        $result = db::query($sql);
        return $result;
    }

    /**
     * 加入考试信息
     */

}
