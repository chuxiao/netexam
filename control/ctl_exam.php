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
        if ($eid <= 0)
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
        $end_time = $begin_time + 35 * 60;
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
        $exam_questions = cache::get("exam_questions", $eid);
        if ($exam_questions == false)
        {
            $exam_questions = pub_mod_question::get_exam_questions($eid);
            if ($exam_questions == false)
            {
                cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
                exit();
            }
            for ($i = 0; $i < $count; ++$i)
            {
                $id = $i + 1;
                $exam_questions[$i]['qid'] = $id;
            }
            cache::set("exam_questions", $eid, $exam_questions);
        }
        $qid_list = array();
        for ($i = 0; $i < $count; ++$i)
        {
            $qid_list[] = $exam_questions[$i]['id'];
        }
        $question_answers = pub_mod_answer::get_question_answer_list($user_id, $qid_list);
        $current_question = false;
        if ($question_answers == false)
        {
            $current_question = $exam_questions[0];
        }
        else
        {
            $last_answer_question = array_pop($question_answers);
            $last_answer_qid = $last_answer_question['question_id'];
            for ($i = 1; $i < $count; ++$i)
            {
                if ($$exam_questions[$i]['id'] > $last_answer_qid)
                {
                    $current_question = $exam_questions[$i];
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
        if ($eid <= 0 || $qid <= 0)
        {
            echo json_encode(array('ret' => -1));
            //cls_msgbox::show('参数错误', '正在为您跳转......', '/?ct=center');
            exit();
        }
        $current_exam = cache::get("exam", $eid);
        if ($current_exam == false)
        {
            echo json_encode(array('ret' => -5));
            //cls_msgbox::show('内部错误', '没有找到相关考试，请联系管理员......', '/?ct=center');
            exit();
        }
        $begin_time = strtotime($current_exam['effect_time']);
        $end_time = $begin_time + 35 * 60;
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
        $count = $current_exam['question_count'];
        if ($qid > $count)
        {
            echo json_encode(array('ret' => -1));
            //cls_msgbox::show('参数错误', '正在为您跳转......', '/?ct=center');
            exit();
        }
        $exam_questions = cache::get("exam_questions", $eid);
        if ($exam_questions == false)
        {
            $exam_questions = pub_mod_question::get_exam_questions($eid);
            if ($exam_questions == false)
            {
                echo json_encode(array('ret' => -5));
                //cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
                exit();
            }
            for ($i = 0; $i < $count; ++$i)
            {
                $id = $i + 1;
                $exam_questions[$i]['qid'] = $id;
            }
            cache::set("exam_questions", $eid, $exam_questions);
        }
        if ($exam_questions == false)
        {
            echo json_encode(array('ret' => -5));
            //cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
            exit();
        }
        $current_question = $exam_questions[$qid - 1];
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
            $next_question = $exam_questions[$qid];
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
        $user_id = pub_mod_auth::get_current_user_id();
        $count = $current_exam['question_count'];
        $exam_questions = cache::get("exam_questions", $eid);
        if ($exam_questions == false)
        {
            $exam_questions = pub_mod_question::get_exam_questions($eid);
            if ($exam_questions == false)
            {
                cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
                exit();
            }
            for ($i = 0; $i < $count; ++$i)
            {
                $id = $i + 1;
                $exam_questions[$i]['qid'] = $id;
            }
            cache::set("exam_questions", $eid, $exam_questions);
        }
        $qid_list = array();
        for ($i = 0; $i < $count; ++$i)
        {
            $qid_list[] = $exam_questions[$i]['id'];
        }
        $answers = pub_mod_answer::get_question_answer_list($user_id, $qid_list);
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
        $exam_score = pub_mod_score::get_exam_score($user_id, $eid);
        if ($exam_score == false)
        {
            pub_mod_score::insert_exam_score($user_id, $eid, $total_score);
        }
        tpl::assign("right", $right);
        tpl::assign("wrong", $wrong);
        tpl::assign("total_score", (int)($total_score / 10));
        tpl::display("exam_stat.tpl");
    }
}
