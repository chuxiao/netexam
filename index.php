<?php
header('Content-Type: text/html; charset=utf-8');
$page_start_time = microtime(true);

require './core/init.php';

run_controller();


