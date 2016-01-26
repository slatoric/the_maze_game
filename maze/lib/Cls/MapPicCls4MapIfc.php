<?php
namespace lib\cls;
class MapPicCls4MapIfc implements \lib\ifc\MapIfc
{
    public static function draw_map(array $aMap)
    {
        foreach($aMap as $iK=>$aLn){
            foreach($aLn as $iJ=>$iV){
                //echo ($iV)?"X":"O";
                if($iV==1)$sV="b";
                elseif($iV==-1)$sV="u";
                else $sV="f";
                echo "<img src='http://{$_SERVER["SERVER_NAME"]}/maze/pic/$sV.png' width='50px'>";
            }
            echo "<br>";
        }
    }
}