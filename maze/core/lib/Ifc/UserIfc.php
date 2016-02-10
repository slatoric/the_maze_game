<?php
namespace core\lib\ifc;
interface UserIfc
{
    public static function is_usr_dta($sLgn);
    public static function get_usr_dta($sLgn);
    public static function set_usr_dta($sLgn,array $aDta);
    public static function del_usr_dta($sLgn);
}