<?php
namespace core\lib\ifc;
interface LngIfc
{
    public static function t($sMsg,$sLng);
    public static function get_lngs();
}