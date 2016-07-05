<?php
if( !defined('CORE') ) exit('Request Error!');

class pub_mod_user_answer
{
    public static function delete_old_data($time)
    {
        for ($i = 0; $i < 8; ++$i)
        {
            $sql = "delete from user_answer_0{$i} where create_time < {$time}";
            db::query($sql);
        }
    }
}
