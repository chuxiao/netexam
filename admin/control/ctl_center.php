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
            exit(header("location: /admin/?ct=login"));
        }
    }

    public function index()
    {
        $now = time();
        // 玩家答题数据只保留30天内的，过期数据清除
        pub_mod_user_answer::delete_old_data($now - 30 * 24 * 60 * 60);

        // 获取考试排期数据
        $exams = pub_mod_exam::get_all_exam_info();
        $date = date("Y-m-d", $now);
        tpl::assign("date", $date);
        tpl::assign("time", date("H:00", $now));
        tpl::assign("exams", $exams);
        tpl::assign("title", "测试中心");
        tpl::display("center.tpl");
    }

    public function upload()
    {

        $date = req::item('date', '');
        $time = req::item('time', '');
        if ($date == '' || $time == '')
        {
            cls_msgbox::show('日期或时间为空', '请重新设置......', '/admin/?ct=center');
        }
        if (!isset(req::$files['file']) || !req::is_upload_file('file'))
        {
            cls_msgbox::show('文件上传失败', '请重新上传......', '/admin/?ct=center');
        }
        $now = time();
        $filename = $GLOBALS['config']['upload_dir'].DIRECTORY_SEPARATOR.$now.".xlsx";
        if (!req::move_upload_file('file', $filename))
        {
            cls_msgbox::show('文件拷贝失败', '请重新上传......', '/admin/?ct=center');
        }
        require_once PATH_LIBRARY.DIRECTORY_SEPARATOR."phpexcel/PHPExcel/IOFactory.php";
        // 当前情况下每期最多100道题
        $filterSubset = new MyReadFilter(2, 101, 'A', 'O');
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objReader->setReadDataOnly(true);
        $objReader->setReadFilter($filterSubset);
        $objPHPExcel = $objReader->load($filename);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $valid_data = array();
        foreach ($sheetData as $entry)
        {
            if (isset($entry['A']) && $entry != null)
            {
                $valid_data[] = $entry;
            }
        }
        $question_count = count($valid_data);
        if ($question_count > 0)
        {
            $exam_id = pub_mod_exam::add_exam_info($date, $time, $now.'.xlsx', $question_count);
            foreach ($valid_data as $entry)
            {
                pub_mod_question::add_question_info($exam_id, $entry['A'], $entry['B'], $entry['C'], $entry['D'], $entry['E'], $entry['F'], $entry['G'], $entry['H'], $entry['I'], $entry['J'], $entry['K'], $entry['L'], $entry['M'], $entry['N'], $entry['O']);
            }
        }
        cls_msgbox::show('上传成功', '正在进行跳转......', '/admin/?ct=center');
    }

    public function show()
    {
        $id = req::item("id", "");
        if ($id == "")
        {
            exit(header("location: /admin/?ct=center"));
        }
        $exam = pub_mod_exam::get_one_exam_info($id);
        $questions = pub_mod_question::get_exam_questions($exam['id']);
        for ($i = 0; $i < count($questions); ++$i)
        {
            $questions[$i]['id'] = $i + 1;
        }
        tpl::assign("exam", $exam);
        tpl::assign("questions", $questions);
        tpl::assign("title", "考试内容");
        tpl::display("questions.tpl");
    }

    public function remove()
    {
        $id = req::item("id", "");
        if ($id == "")
        {
            exit(header("location: /admin/?ct=center"));
        }
        $exam = pub_mod_exam::get_one_exam_info($id);
        $filename = $GLOBALS['config']['upload_dir'].DIRECTORY_SEPARATOR.$exam['file_name'];
        @unlink($filename);
        pub_mod_exam::delete_exam_info($id);
        exit(header("location: /admin/?ct=center"));
    }
}
require_once PATH_LIBRARY.DIRECTORY_SEPARATOR."phpexcel/PHPExcel/IOFactory.php";
class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
    private $min_row;
    private $max_row;
    private $min_col;
    private $max_col;

    public function __construct($min_row, $max_row, $min_col, $max_col)
    {
        $this->min_row = $min_row;
        $this->max_row = $max_row;
        $this->min_col = $min_col;
        $this->max_col = $max_col;
    }

    public function readCell($column, $row, $worksheetName = '') {
        if ($row >= $this->min_row && $row <= $this->max_row) {
            if (in_array($column,range($this->min_col, $this->max_col))) {
                return true;
            }
        }
        return false;
    }
}
