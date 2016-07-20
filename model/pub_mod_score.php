<?php
if( !defined('CORE') ) exit('Request Error!');

class pub_mod_score
{
    public static function insert_exam_score($user_id, $eid, $score)
    {
        $table_name = pub_mod_common::get_split_table($user_id, "user_exam");
        $params = array('user_id' => $user_id, 'eid' => $eid, 'score' => $score);
        return db::insert($params, $table_name);
    }

    public static function get_exam_score_top($eid, $top = 10)
    {
        $tmp_results = array();
        $idx_arr = array();
        $count_arr = array();
        $total_count = 0;
        for ($i = 0; $i < 8; ++$i)
        {
            $sql = "SELECT *FROM user_exam_0".$i." WHERE eid = ".$eid." ORDER BY score DESC LIMIT ".$top;
            db::query($sql);
            $query_result = db::fetch_all();
            if ($query_result == false)
            {
                $query_result = array();
            }
            $tmp_results[] = $query_result;
            $idx_arr[] = 0;
            $count_arr[] = count($query_result);
            $total_count += $count_arr[$i];
        }
        if ($total_count == 0)
        {
            return false;
        }
        if ($top > $total_count)
        {
            $top = $total_count;
        }
        $result = array();
        for ($i = 0; $i < $top; ++$i)
        {
            $idx = 0;
            $tmp_score = 0;
            while ($idx_arr[$idx] >= $count_arr[$idx])
            {
                ++$idx;
            }
            $tmp_score = $tmp_results[$idx][$idx_arr[$idx]]['score'];
            for ($j = $idx + 1; $j < 8; ++$j)
            {
                if ($idx_arr[$j] >= $count_arr[$j])
                {
                    continue;
                }
                $tmp_score2 = $tmp_results[$j][$idx_arr[$j]]['score'];
                if ($tmp_score < $tmp_score2)
                {
                    $tmp_score = $tmp_score2;
                    $idx = $j;
                }
            }
            if ($tmp_score != 0)
            {
                $result[] = $tmp_results[$idx][$idx_arr[$idx]];
            }
            ++$idx_arr[$idx];
        }
        return $result;
    }
}
