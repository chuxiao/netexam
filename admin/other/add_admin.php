<?php
if ($argc < 3)
{
    echo "Usage: php add_admin.php user_name password.\n";
    exit(-1);
}
$user_name = $argv[1];
$password = $argv[2];

$sql = "insert user_admin (user_name, passwd) value ('".$user_name."', '".$password."');\n";
echo $sql;
