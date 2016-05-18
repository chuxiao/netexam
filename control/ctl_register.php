<?php
if( !defined('CORE') ) exit('Request Error!');
/**
 * index控制器
 *
 * @version $id
 */
class ctl_register
{
    private static $title = "注册用户";
    public function __construct()
    {
        tpl::assign("title", self::$title);
    }

    public function index()
    {
        tpl::display("register.tpl");
    }

    public function register()
    {
        // TODO:
    }

    public function verify_code()
    {
        $code = new mod_captcha;
        $code->code = $code->make_seccode();                           // 验证码
        $code->type = 0;                                               // 0英文图片验证码 1中文图片验证码 2Flash 验证码 3语音验证码 4位图验证码
        $code->width = 100;                                            // 验证码宽度
        $code->height = 30;                                            // 验证码高度
        $code->background = 1;                                         // 是否随机图片背景
        $code->adulterate = 1;                                         // 是否随机背景图形
        $code->ttf = 0;                                                // 是否随机使用ttf字体
        $code->angle = 0;                                              // 是否随机倾斜度
        $code->warping = 0;                                            // 是否随机扭曲
        $code->scatter = 0;                                            // 是否图片打散
        $code->color = 1;                                              // 是否随机颜色
        $code->size = 0;                                               // 是否随机大小
        $code->shadow = 1;                                             // 是否文字阴影
        $code->animator = 0;                                           // 是否GIF 动画
        $code->fontpath = './static/captcha/image/seccode/font/';      // 字体路径
        $code->datapath = './static/captcha/image/seccode/';           // 数据路径
        $code->display();
    }
}
