<?php
namespace core\lib\trt;
trait LngTrt
{
    private static $sCls4LngIfc="core\lib\cls\LngFileCls4LngIfc";
    private static $sLng;
    public static function get_sCls4LngIfc(){
        return self::$sCls4LngIfc;
    }
    public static function set_sCls4LngIfc($sV){
        try{
            if(!$sV)throw new ExcCls("No class",ExcCls::DEBUG);
            if(!class_exists($sV))throw new ExcCls("No match class for language interface",ExcCls::DEBUG);
            self::$sCls4LngIfc=$sV;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sCls;}
    }
    public static function t($sMsg){
        try{
            if(!class_exists($sCls4LngIfc=self::$sCls4LngIfc))throw new ExcCls("No class for language interface",ExcCls::DEBUG);
            if(!$sMsg)throw new ExcCls("No message",ExcCls::DEBUG);
            $sMsgr=$sCls4LngIfc::t($sMsg,self::$sLng);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sMsgr;}
    }
    public static function show_sel($sNm,$sLng=null){
        try{
            if(!class_exists($sCls4LngIfc=self::$sCls4LngIfc))throw new ExcCls("No class for language interface",ExcCls::DEBUG);
            if(!$aLng=$sCls4LngIfc::get_lngs())throw new ExcCls("No language files",ExcCls::DEBUG);
            if(count($aLng)>1)foreach($aLng as $sLnc=>$sLnn){
                $sSld=($sLng&&$sLng==$sLnc)?" selected='selected'":"";
                if($sLnc)$sSls.="<option value='{$sLnc}'{$sSld}>{$sLnn}</option>";}
            if($sSls)$sSel="<select name='{$sNm}'>{$sSls}</select>";
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sSel;}
    }
    public static function get_lng(){
        try{
            if(!class_exists($sCls4LngIfc=self::$sCls4LngIfc))throw new ExcCls("No class for language interface",ExcCls::DEBUG);
            $sLng=self::$sLng;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sSel;}
        return $sLng;
    }
    public static function set_lng($sLng){
        try{
            if(!class_exists($sCls4LngIfc=self::$sCls4LngIfc))throw new ExcCls("No class for language interface",ExcCls::DEBUG);
            $sLng=self::$sLng=($sLng)?:$sCls4LngIfc::$sLngDef;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sLng;}
    }
}