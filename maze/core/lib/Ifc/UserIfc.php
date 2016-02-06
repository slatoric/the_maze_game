<?php
namespace core\lib\ifc;
interface UserIfc
{
    public static function get_user_data($sLgn,$sPsw,$sSes);
    public static function set_user_data($sLgn,$sPsw,$sSes,array $aDta);
}