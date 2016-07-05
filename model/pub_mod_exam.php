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
        $sql = "SELECT * FROM exam";
        db::query($sql);
        return db::fetch_all();
    }

    public static function get_one_exam_info($exam_id)
    {
        $sql = "SELECT * FROM exam WHERE id = ".$exam_id;
        db::query($sql);
        return db::fetch_one();
    }

    /**
     * 加入考试信息
     */
    public static function add_exam_info($date, $time, $file_name, $question_count)
    {
        $params = array('effect_time' => $date.' '.$time, 'file_name' => $file_name, 'question_count' => $question_count);
        db::insert($params, 'exam');
        return db::insert_id();
    }

    public static function delete_exam_info($id)
    {
        $sql = "DELETE FROM exam WHERE id = ".$id;
        db::query($sql);
    }

    public static function get_exam_info_day($time)
    {
        $day = date('Y-m-d 00:00:00', $time);
        $next_day = date('Y-m-d 00:00:00', $time + 24 * 60 * 60);
        $sql = "SELECT * FROM exam WHERE effect_time >= '".$day."' and effect_time < '".$next_day."'";
        db::query($sql);
        return db::fetch_all();
    }

    public static function get_next_exam_info($time)
    {
        $sql = "SELECT * FROM exam WHERE effect_time >= '".$time."' ORDER BY id ASC";
        db::query($sql);
        return db::fetch_one();
    }

    public static function get_prev_exam_info($time)
    {
        $sql = "SELECT * FROM exam WHERE effect_time < '".$time."' ORDER BY id DESC";
        db::query($sql);
        return db::fetch_one();
    }

}
