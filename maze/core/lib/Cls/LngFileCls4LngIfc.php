<?php
namespace core\lib\cls;
class LngFileCls4LngIfc implements \core\lib\ifc\LngIfc
{
    const PTH="/core/lib/lng/";
    const LNG_DEF="en";
    private static $sLngDef=self::LNG_DEF;
    private static $sLng;
    public static function t($sMsg,$sLng=null){
        try{
            if(!$sMsg)throw new ExcCls("No message",ExcCls::DEBUG);
            self::$sLng=($sLng)?:self::$sLngDef;
            if(!file_exists($sFnm=BDR.self::PTH.self::$sLng))//try to create language file
                if(!self::set_ln(self::$sLng,"wb"))throw new ExcCls("No create language file",ExcCls::DEBUG);
            if(!file_exists($sFnm))throw new ExcCls("No language file",ExcCls::DEBUG);
            if(!$sFct=file_get_contents($sFnm))throw new ExcCls("No read language file",ExcCls::DEBUG);
            if(strpos($sFct,$sMsg)===false){//try to append new message
                if(!self::set_ln("$sMsg\n","ab"))throw new ExcCls("No append language file",ExcCls::DEBUG);
            }elseif(self::$sLng!=self::$sLngDef){//message found
                if(!$aLns=file($sFnm,FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES))throw new ExcCls("No array language file",ExcCls::DEBUG);
                foreach($aLns as $sLn){
                    if(!$aLn=self::tln($sLn))throw new ExcCls("No parse line",ExcCls::DEBUG);
                    if(($aLn[0]==$sMsg)and(count($aLn)>1)){
                        $sT=$aLn[1];
                        break;
                    }
                }
            }
            $sT=($sT)?:$sMsg;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sT;}
    }
    public static function set_ln($sLn,$sMd){
        try{
            if(!$sLn)throw new ExcCls("No line",ExcCls::DEBUG);
            if(!$sMd)throw new ExcCls("No mode",ExcCls::DEBUG);
            if(!$mF=fopen($sFnm,$sMd))throw new ExcCls("No open language file",ExcCls::DEBUG);
            if(!$bWrt=fwrite($mF,self::$sLng."\n"))throw new ExcCls("No write language file",ExcCls::DEBUG);
            if(!$bCls=fclose($mF))throw new ExcCls("No close language file",ExcCls::DEBUG);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bCls;}
    }
    public static function tln($sLn){
        try{
            if(!$sLn)throw new ExcCls("No line",ExcCls::DEBUG);
            if(!$aLn=explode("<==>",trim($sLn)))throw new ExcCls("No explode line",ExcCls::DEBUG);
            if(count($aLn)>2)array_splice($aLn,2);
            foreach($aLn as $iK=>$sL)$aLn[$iK]=trim($sL);//clean up
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aLn;}
    }
    public static function get_lnm($sLng){
        try{
            if(!$sLng)throw new ExcCls("No language code",ExcCls::DEBUG);
            if(!file_exists($sFnm=BDR.self::PTH.$sLng))throw new ExcCls("No language file",ExcCls::DEBUG);
            if(!$rF=fopen($sFnm,"r"))throw new ExcCls("No read language file",ExcCls::DEBUG);
            if(!$sLn=fgets($rF))throw new ExcCls("No read first line",ExcCls::DEBUG);
            if(!$aLn=self::tln($sLn))throw new ExcCls("No parse first line",ExcCls::DEBUG);
            $sLng_nm=($aLn[1])?:$sLng;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sLng_nm;}
    }
    public static function get_lng(){
        return self::$sLng;
    }
    public static function set_lng($sLng){
        self::$sLng=($sLng)?:self::$sLngDef;
        return self::$sLng=$sLng;
    }
    public static function get_lngs(){
        if($rD=opendir(BDR.self::PTH)){
            while(($sF=readdir($rD))!==false)
                if($sF!=".."&&$sF!=".")$aLng[$sF]=self::get_lnm($sF);
            closedir($rD);}
        if(!$aLng)$aLng[self::LNG_DEF]=self::get_lnm(self::LNG_DEF);
        return $aLng;
    }
}