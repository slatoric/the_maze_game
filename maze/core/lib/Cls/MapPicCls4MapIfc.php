<?php
namespace core\lib\cls;
class MapPicCls4MapIfc implements \core\lib\ifc\MapIfc
{
    const PTH="/core/pic/";
    public function show_map(array $aMap)
    {
        foreach($aMap as $iK=>$aLn){
            foreach($aLn as $iJ=>$oT){
                if(is_object($oT)){
                    if($oT->is_visible()){
                        $sV=($oT->is_wall())?"b":"f";
                        $sV=($oT->is_user())?"g":$sV;
                        }
                    else $sV="u";}
                echo "<img src='http://{$_SERVER["SERVER_NAME"]}/maze".self::PTH."$sV.png' width='50px'>";
            }
            echo "<br>";
        }
    }
}