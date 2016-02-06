<?php
namespace core\lib\cls;
class UserFileCls4UserIfc implements \core\lib\ifc\UserIfc
{
    const PTH="/db/usr/";
    public static function is_user_data($sLgn){
        try{
            if(!$sLgn)throw new ExcCls("No login",ExcCls::DEBUG);
            if(!$bF=file_exists(BDR.self::PTH.$sLgn))throw new ExcCls("No login file",ExcCls::DEBUG);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bF;}
    }
    public static function get_user_data($sLgn){
        try{
            if(self::is_user_data($sLgn)){
                if(!$sF=file_get_contents(BDR.self::PTH.$sLgn))throw new ExcCls("No read login file",ExcCls::DEBUG);
                if(!$aDta=unserialize($sF))throw new ExcCls("No unserialize login data",ExcCls::DEBUG);
            }
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aDta;}
    }
    public static function set_user_data($sLgn,array $aDta){
        try{
            if(!$sLgn)throw new ExcCls("No login",ExcCls::DEBUG);
            if(!$aDta)throw new ExcCls("No login data",ExcCls::DEBUG);
            if(!$rF=fopen(BDR.self::PTH.$sLgn,"w"))throw new ExcCls("No open login file",ExcCls::DEBUG);
            if(!$sDta=serialize($aDta))throw new ExcCls("No serialize login data",ExcCls::DEBUG);
            if(!$bWrt=fwrite($rF,$sDta))throw new ExcCls("No write login file",ExcCls::DEBUG);
            if(!fclose($rF))throw new ExcCls("No close login file",ExcCls::DEBUG);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bWrt;}
    }
    public static function del_user_data($sLgn){
        try{
            if(!$sLgn)throw new ExcCls("No login",ExcCls::DEBUG);
            if(!$rF=fopen($sFnm=BDR.self::PTH.$sLgn,"r"))throw new ExcCls("No open login file",ExcCls::DEBUG);
            if(!fclose($rF))throw new ExcCls("No close login file",ExcCls::DEBUG);
            if(!$bDel=unlink($sFnm))throw new ExcCls("No delete login file",ExcCls::DEBUG);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bDel;}
    }
}