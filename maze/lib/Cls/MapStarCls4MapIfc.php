<?php
namespace lib\cls;
class MapStarCls4MapIfc implements \lib\ifc\MapIfc
{
    public static function draw_map(array $aMap)
    {
        foreach($aMap as $iK=>$aLn){
            foreach($aLn as $iJ=>$iV){
                //echo ($iV)?"X":"O";
                if($iV==1)$sV="X";
                elseif($iV==-1)$sV="Y";
                else $sV="O";
                echo $sV;
            }
            echo "<br>";
        }
    }
}