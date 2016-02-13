<?php
namespace core\lib\cls;
class MapPicCls4MapIfc implements \core\lib\ifc\MapIfc
{
    const PTH="/core/pic/";
    public static function show_map(array $aMap){
        try{
            $sSdr=strtolower(str_replace("/",DIRECTORY_SEPARATOR,SDR));//site root dir
            $sPth=str_replace($sSdr,"",strtolower(__FILE__));
            $sPth2core=strstr($sPth,"core",true);
            $sPth2core=(in_array($sPth2core,["/","\\"]))?"":$sPth2core;//if core is in basedir return empty string
            if(!$aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            foreach($aMap as $iK=>$aLn){
                foreach($aLn as $iJ=>$oT){
                    if(!is_object($oT))throw new ExcCls("No tile",ExcCls::DEBUG);
                    if($oT->is_vis()){
                        $sV=($oT->is_wll())?"b":"f";
                        $sV=($oT->is_usr())?"g":$sV;
                    }else $sV="u";
                    $sHtm.="<img src='http://{$_SERVER["SERVER_NAME"]}$sPth2core".self::PTH."$sV.png' width='50px'>";
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