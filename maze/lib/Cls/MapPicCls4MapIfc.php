<?php
namespace lib\cls;
class MapPicCls4MapIfc implements \lib\ifc\MapIfc
{
    public function draw(array $aMap)
    {
        foreach($aMap as $iK=>$aLn){
            foreach($aLn as $iJ=>$oT){
                //echo "<pre>";var_dump($oT);echo "</pre>";
                if(is_object($oT)){
                    if($oT->is_visible()){
                        $sV=($oT->is_wall())?"b":"f";
                        $sV=($oT->is_user())?"g":$sV;
                        }
                    else $sV="u";
                    }
                echo "<img src='http://{$_SERVER["SERVER_NAME"]}/maze/pic/$sV.png' width='50px'>";
            }
            echo "<br>";
        }
    }
}