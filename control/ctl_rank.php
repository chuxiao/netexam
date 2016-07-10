<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_rank
{
    public function __construct()
    {
        if (!pub_mod_auth::is_login())
        {
            exit(header("location: /?ct=login"));
        }
        tpl::assign("title", "排行榜");
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
        $rank_list = cache::get("rank", $eid);
        if ($rank_list == false)
        {
            $rank_list = pub_mod_score::get_exam_score_top($eid, 10);
            for ($i = 0; $i < count($rank_list); ++$i)
            {
                $user_detail = pub_mod_user::get_one_user_details($rank_list[$i]['user_id']);
                $rank_list[$i]['user_name'] = $user_detail['nickname'];
                $rank_list[$i]['rank'] = $i + 1;
            }
            cache::set("rank", $eid, $rank_list);
        }
        $prev_eid = 0;
        $prev_exam = pub_mod_exam::get_prev_exam_info($current_exam['effect_time']);
        if ($prev_exam)
        {
            $prev_eid = $prev_exam['id'];
        }
        tpl::assign("prev_eid", $prev_exam['id']);
        tpl::assign("rank_list", $rank_list);
        tpl::display("rank.tpl");
    }
}
