<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_center
{
    public function __construct()
    {
        if (!pub_mod_auth::is_login())
        {
            exit(header("location: /?ct=login"));
        }
        tpl::assign("title", "考试中心");
    }

    public function index()
    {
        $now = time();
        $exams = pub_mod_exam::get_exam_info_day($now);
        $current_exam = false;
        if ($exams != false)
        {
            foreach ($exams as $exam)
            {
                $effect_time = strtotime($exam['effect_time']);
                $end_time = $effect_time + 10 * 60;
                if ($now >= $effect_time && $now < $end_time)
                {
                    $current_exam = $exam;
                    break;
                }
            }
        }
        $exam_str = "暂无考试信息";
        if ($current_exam != false)
        {
            $effect_time = strtotime($current_exam['effect_time']);
            $user_id = pub_mod_auth::get_current_user_id();
            $eid = $current_exam['id'];
            $exam_score = pub_mod_score::get_exam_score($user_id, $eid);
            if ($exam_score == false)
            {
                $exam_str = "<a href=\"/?ct=exam&eid=".$eid."\">开始考试</a>";
            }
            else
            {
                $exam_str = "<a href=\"/?ct=exam&ac=over&eid=".$eid."\">查看考试结果</a>";
            }
        }
        else
        {
            $next_exam = pub_mod_exam::get_next_exam_info($now);
            if ($next_exam != false)
            {
                $exam_str = "下一场考试时间: ".$next_exam['effect_time'];
            }
        }
        $rank_str = "暂无排行榜信息";
        $prev_exam = pub_mod_exam::get_prev_exam_info($now - 40 * 60);
        if ($prev_exam != false)
        {
            $rank_str = "<a href=\"/?ct=rank&eid=".$prev_exam['id']."\">查看排行榜</a>";
        }
        tpl::assign("exam_str", $exam_str);
        tpl::assign("rank_str", $rank_str);
        tpl::display("center.tpl");
    }
}
