<?php
namespace lib\cls;
class MapStdCls4MapIfc implements \lib\ifc\MapIfc
{
    public static function draw_map(array $aMap)
    {
        foreach($aMap as $iK=>$aLn){
            foreach($aLn as $iJ=>$iV){
                echo $iV;
            }
            echo "<br>";
        }
    }
}