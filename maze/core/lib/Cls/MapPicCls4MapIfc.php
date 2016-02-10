<?php
namespace core\lib\cls;
class MapPicCls4MapIfc implements \core\lib\ifc\MapIfc
{
    const PTH="/core/pic/";
    public static function show_map(array $aMap){
        try{
            if(!$aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            foreach($aMap as $iK=>$aLn){
                foreach($aLn as $iJ=>$oT){
                    if(!is_object($oT))throw new ExcCls("No tile",ExcCls::DEBUG);
                    if($oT->is_vis()){
                        $sV=($oT->is_wll())?"b":"f";
                        $sV=($oT->is_usr())?"g":$sV;
                    }else $sV="u";
                    $sHtm.="<img src='http://{$_SERVER["SERVER_NAME"]}/maze".self::PTH."$sV.png' width='50px'>";
                }
            $sHtm.="<br>";
            }
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sHtm;}
        
    }
}