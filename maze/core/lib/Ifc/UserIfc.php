<?php
namespace core\lib\ifc;
interface UserIfc
{
    public static function is_user_data($sLgn);
    public static function get_user_data($sLgn);
    public static function set_user_data($sLgn,array $aDta);
    public static function del_user_data($sLgn);
}