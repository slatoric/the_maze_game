<?php
namespace core\lib\ifc;
interface UserIfc
{
    public static function get_user($sLgn,$sPsw,$sSes);
    public static function set_user($sLgn,$sPsw,$sSes,array $aDta);
}