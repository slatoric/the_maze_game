<?php
namespace lib\ifc;
interface UserIfc
{
    public static function get_data($sLgn,$sPsw,$sSes);
    public static function set_data($sLgn,$sPsw,$sSes,array $aDta);
}