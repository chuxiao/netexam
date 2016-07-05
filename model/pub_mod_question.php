<?php
if( !defined('CORE') ) exit('Request Error!');

class pub_mod_question
{
    public static function add_question_info($eid, $question, $A, $B, $C, $D, $E, $F, $G, $H, $I, $J, $answer, $score, $timer, $keep_time)
    {
        $params = array('eid' => $eid, 'question' => $question, 'A' => $A, 'B' => $B, 'C' => $C, 'D' => $D, 'E' => $E, 'G' => $G, 'H' => $H, 'I' => $I, 'J' => $J,
            'answer' => $answer, 'score' => $score, 'timer' => $timer, 'keep_time' => $keep_time);
        db::insert($params, 'question');
    }

    public static function get_exam_questions($eid)
    {
        $sql = "SELECT *FROM question WHERE eid = ".$eid.' ORDER BY id ASC';
        db::query($sql);
        return db::fetch_all();
    }
}
