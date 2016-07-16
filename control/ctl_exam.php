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
        $qid = req::item('qid', 0);
        if ($eid == 0 || $qid == 0)
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
        $count = $current_exam['question_count'];
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
            $current_question = $questions[0];
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
        if ($eid == 0 || $qid == 0)
        {
            cls_msgbox::show('参数错误', '正在为您跳转......', '/?ct=center');
            exit();
        }
        $current_question = cache::get("question", $eid."_".$qid);
        if ($current_question == false)
        {
            cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
            exit();
        }
        $id = $current_question['id'];
        $score = $current_question['score'];
        if ($result != $current_question['answer'])
        {
            $score = 0;
        }
        $account = pub_mod_auth::get_current_user_id();
        pub_mod_answer::insert_question_answer($account, $id, $result, $score);
        $current_exam = cache::get("exam", $eid);
        if ($current_exam == false)
        {
            cls_msgbox::show('内部错误', '没有找到相关考试，请联系管理员......', '/?ct=center');
            exit();
        }
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
                cls_msgbox::show('内部错误', '没有找到相关考题，请联系管理员......', '/?ct=center');
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
        $begin_time = strtotime($current_exam['effect_time']);
        $end_time = time();
        $account = pub_mod_auth::get_current_user_id();
        $answers = pub_mod_answer::get_question_answer_duration($account, $begin_time, $end_time);
        $right = 0;
        $wrong = 0;
        $total_score = 0;
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
            pub_mod_score::insert_exam_score($account, $eid, $total_score);
        }
        tpl::assign("right", $right);
        tpl::assign("wrong", $wrong);
        tpl::assign("total_score", $total_score);
        tpl::display("exam_stat.tpl");
    }
}
