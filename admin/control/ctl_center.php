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
        $date = date("Y-m-d", $now);
        $datelist = cache::get("", "datelist");
        if (!isset($datelist))
        {
            $datelist = array();
        }
        $newlist = array();
        foreach ($datelist as $k => $v)
        {
            if ($k < $date)
            {
                cache::del("", $v);
            }
            else
            {
                $newlist[$k] = $v;
            }
        }
        cache::set("", "datelist", $newlist, 0);
        $showlist = array();
        foreach ($newlist as $k => $v)
        {
            $showlist[] = array("date" => $k, "time" => $v);
        }
        tpl::assign("date", $date);
        tpl::assign("time", date("H:i", $now));
        tpl::assign("datelist", $showlist);
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
        $filename = $GLOBALS['config']['upload_dir'].DIRECTORY_SEPARATOR.$date.".xlsx";
        if (!req::move_upload_file('file', $filename))
        {
            cls_msgbox::show('文件拷贝失败', '请重新上传......', '/admin/?ct=center');
        }
        require_once PATH_LIBRARY.DIRECTORY_SEPARATOR."phpexcel/PHPExcel/IOFactory.php";
        $filterSubset = new MyReadFilter();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objReader->setReadDataOnly(true);
        $objReader->setReadFilter($filterSubset);
        $objPHPExcel = $objReader->load($filename);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $datelist = cache::get("", "datelist");
        if (!isset($datelist))
        {
            $datelist = array();
        }
        $datelist[$date] = $time;
        cache::set("", "datelist", $datelist, 0);
        cache::set('', $date, $sheetData, 0);
        cls_msgbox::show('上传成功', '正在进行跳转......', '/admin/?ct=center');
    }
}
require_once PATH_LIBRARY.DIRECTORY_SEPARATOR."phpexcel/PHPExcel/IOFactory.php";
class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
    public function readCell($column, $row, $worksheetName = '') {
        if ($row >= 1 && $row <= 7) {
            if (in_array($column,range('A','E'))) {
                return true;
            }
        }
        return false;
    }
}
