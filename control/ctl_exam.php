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
            for ($i = 0; $i < $count; ++i)
            {
                $id = $i + 1;
                $questions[$i]['id'] = $id;
                cache::set("question", $eid."_".$id, $q);
            }
            $current_question = $questions[0];
        }

        tpl::assign("total_count", $count);
        tpl::assign("question", $current_question);
        tpl::display("exam.tpl");
    }
}
