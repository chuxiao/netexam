<?php
header('Content-Type: text/html; charset=utf-8');
$page_start_time = microtime(true);

require './core/init.php';

/*if (util::get_client_ip() != '119.145.100.138')
{
    exit;
}*/
$config_pool_name = $config_appname  =  $config_cp_url = '';

// 取得所有已经开通的游戏列表 mod_pay类调用了，mod_game原来调用，后来去掉了
$game_list = mod_game::get_game_list(true);
$GLOBALS['game'] = array();
foreach ($game_list as $val)
{
    $GLOBALS['game'][$val['id']] = $val;
}
//echo '<pre>';print_r($GLOBALS['game']);echo '</pre>';

run_controller();


