<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_exam
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
        $eid = req::item('eid', 0);
        if ($eid == 0)
        {
            cls_msgbox::show('参数错误', '正在为您跳转......', '/?ct=center');
            exit();
        }
        $current_exam = cache::get("exam", $eid);
        if ($current_exam == false)
        {
            $current_exam = pub_mod_exam::get_one_exam_info($eid);
            if ($current_exam == false)
            {
                cls_msgbox::show('内部错误', '没有找到相关考试，请联系管理员......', '/?ct=center');
                exit();
            }
            cache::set("exam", $eid, $current_exam);
        }
        $begin_time = strtotime($current_exam['effect_time']);
        $end_time = $begin_time + 40 * 60;
        $now = time();
        if ($now < $begin_time || $now > $end_time)
        {
            cls_msgbox::show('参数错误', '考试已过或未开始，正在为您跳转......', '/?ct=center');
            exit();
        }
        $user_id = pub_mod_auth::get_current_user_id();
        $exam_score = pub_mod_score::get_exam_score($user_id, $eid);
        if ($exam_score != false)
        {
            cls_msgbox::show('系统提示', '答题已结束，请查看结果......', '/?ct=exam&ac=over&eid='.$eid);
            exit();
        }
        $count = $current_exam['question_count'];
        $qid = 1;
        $current_question = cache::get("question", $eid."_".$qid);
        if ($current_question == false)
        {
            $questions = pub_mod_question::get_exam_questions($eid);
            if ($questions == false)
            {
                cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
                exit();
            }
            for ($i = 0; $i < $count; ++$i)
            {
                $id = $i + 1;
                $questions[$i]['qid'] = $id;
                cache::set("question", $eid."_".$id, $questions[$i]);
            }
        }
        $current_question = false;
        $question_answers = pub_mod_answer::get_question_answer_duration($user_id, $begin_time, $end_time);
        if ($question_answers == false)
        {
            $current_question = $questions[0];
        }
        else
        {
            $last_answer_question = array_pop($question_answers);
            $last_answer_qid = $last_answer_question['question_id'];
            for ($i = 2; $i <= $count; ++$i)
            {
                $current_question = cache::get("question", $eid."_".$i);
                if ($current_question == false)
                {
                    cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
                    exit();
                }
                if ($current_question['id'] > $last_answer_qid)
                {
                    break;
                }
            }
        }
        if ($current_question == false)
        {
            cls_msgbox::show('系统提示', '答题已结束，请查看结果......', '/?ct=exam&ac=over&eid='.$eid);
            exit();
        }
        tpl::assign("total_count", $count);
        tpl::assign("question", $current_question);
        tpl::display("exam.tpl");
    }

    public function next_q()
    {
        $eid = req::item('eid', 0);
        $qid = req::item('qid', 0);
        $result = req::item('result', '');
        $cost = req::item('cost', 1);
        if ($eid == 0)
        {
            echo json_encode(array('ret' => -1));
            //cls_msgbox::show('参数错误', '正在为您跳转......', '/?ct=center');
            exit();
        }
        $current_exam = cache::get("exam", $eid);
        if ($current_exam == false)
        {
            echo json_encode(array('ret' => -2));
            //cls_msgbox::show('内部错误', '没有找到相关考试，请联系管理员......', '/?ct=center');
            exit();
        }
        $begin_time = strtotime($current_exam['effect_time']);
        $end_time = $begin_time + 40 * 60;
        $now = time();
        if ($now < $begin_time || $now > $end_time)
        {
            echo json_encode(array('ret' => -3));
            //cls_msgbox::show('参数错误', '考试已过或未开始，正在为您跳转......', '/?ct=center');
            exit();
        }
        $user_id = pub_mod_auth::get_current_user_id();
        $exam_score = pub_mod_score::get_exam_score($user_id, $eid);
        if ($exam_score != false)
        {
            echo json_encode(array('ret' => -4));
            //cls_msgbox::show('系统提示', '答题已结束，请查看结果......', '/?ct=exam&ac=over&eid='.$eid);
            exit();
        }
        $current_question = cache::get("question", $eid."_".$qid);
        if ($current_question == false)
        {
            echo json_encode(array('ret' => -5));
            //cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
            exit();
        }
        $current_answer = pub_mod_answer::get_question_answer($user_id, $current_question['id']);
        if ($current_answer != false)
        {
            echo json_encode(array('ret' => -6));
            //cls_msgbox::show('内部错误', '此题已答......', '/?ct=center');
            exit();
        }
        $id = $current_question['id'];
        if ($result != $current_question['answer'])
        {
            $score = 0;
        }
        else
        {
            $score = (int)($current_question['score'] * 10 * ($current_question['timer'] - $cost) / $current_question['timer']);
        }
        pub_mod_answer::insert_question_answer($user_id, $id, $result, $score);
        if ($qid == $current_exam['question_count'])
        {
            echo json_encode(array('ret' => 2));
            exit();
        }
        else
        {
            ++$qid;
            $next_question = cache::get("question", $eid."_".$qid);
            if ($next_question == false)
            {
                echo json_encode(array('ret' => -7));
                //cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
                exit();
            }
            $next_question['ret'] = 1;
            echo json_encode($next_question);
        }
    }

    public function over()
    {
        $eid = req::item('eid', 0);
        if ($eid == 0)
        {
            cls_msgbox::show('参数错误', '正在为您跳转......', '/?ct=center');
            exit();
        }
        $current_exam = cache::get("exam", $eid);
        if ($current_exam == false)
        {
            cls_msgbox::show('内部错误', '没有找到相关考试，请联系管理员......', '/?ct=center');
            exit();
        }
        $right = 0;
        $wrong = 0;
        $total_score = 0;
        $begin_time = strtotime($current_exam['effect_time']);
        $end_time = time();
        $account = pub_mod_auth::get_current_user_id();
        $answers = pub_mod_answer::get_question_answer_duration($account, $begin_time, $end_time);
        if ($answers != false)
        {
            if ($answers != false)
            {
                foreach ($answers as $v)
                {
                    if ($v['score'] > 0)
                    {
                        ++$right;
                        $total_score += $v['score'];
                    }
                    else
                    {
                        ++$wrong;
                    }
                }
            }
        }
        $exam_score = pub_mod_score::get_exam_score($account, $eid);
        if ($exam_score == false)
        {
            pub_mod_score::insert_exam_score($account, $eid, $total_score);
        }
        tpl::assign("right", $right);
        tpl::assign("wrong", $wrong);
        tpl::assign("total_score", (int)($total_score / 10));
        tpl::display("exam_stat.tpl");
    }
}
