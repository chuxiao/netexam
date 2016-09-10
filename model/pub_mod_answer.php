<?php
if( !defined('CORE') ) exit('Request Error!');

class pub_mod_answer
{
    public static function insert_question_answer($user_id, $qid, $result, $score)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "user_answer");
        $now = time();
        $params = array('user_id' => $user_id, 'question_id' => $qid, 'answer' => $result, 'score' => $score, 'create_time' => $now);
        return db::insert($params, $table_name);
    }

    public static function get_question_answer_list($user_id, $question_list)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "user_answer");
        $sql = "SELECT *FROM ".$table_name." WHERE user_id = ".$user_id." AND question_id in (".implode(",", $question_list).")  ORDER BY create_time ASC";
        db::query($sql);
        return db::fetch_all();
    }

    public static function get_question_answer($user_id, $question_id)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "user_answer");
        $sql = "SELECT *FROM ".$table_name." WHERE user_id = ".$user_id." AND question_id = ".$question_id;
        db::query($sql);
        return db::fetch_one();
    }
}
