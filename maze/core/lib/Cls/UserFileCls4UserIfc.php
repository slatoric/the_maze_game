<?php
namespace core\lib\cls;
class UserFileCls4UserIfc implements \core\lib\ifc\UserIfc
{
    const PTH="/db/usr/";
    public function t($sMsg){
        return LngFileCls4LngIfc::t($sMsg);
    }
    public static function get_user_data($sLgn,$sPsw,$sSes){
        try{
            if(!$sLgn)throw new ExcCls(self::t("No login"),ExcCls::INFO);
            if(!$sPsw&&!$sSes)throw new ExcCls(self::t("No password and no session"),ExcCls::INFO);
            if(!file_exists($sFnm=BDR.self::PTH.$sLgn))throw new ExcCls(self::t("No user file"),ExcCls::INFO);
            $aDta=unserialize(file_get_contents($sFnm));
            if((!password_verify($sPsw,$aDta["psw"]))and($aDta["ses"]!=$sSes))throw new ExcCls(self::t("No user permission"),ExcCls::INFO);
            $aDtr=$aDta;}
        catch(ExcCls $e){
            $e->man();
            throw $e;}
        finally{
            return $aDtr;}
    }
    public static function set_user_data($sLgn,$sPsw,$sSes,array $aDta){
        if(($sLgn)and($sPsw||$sSes)){
            if($mDtw=self::get_user_data($sLgn,$sPsw,$sSes)){
                if(password_verify($sPsw,$mDtw["psw"]))
                    $sPswr=($aDta["psw_new"])?:$sPsw;
                elseif(!$sPsw)$sPswr=$mDtw["psw"];
                $aDta["lgn"]=$sLgn;
                $aDta["psw"]=password_hash($sPswr,PASSWORD_DEFAULT);
                $aDta["ses"]=$sSes;
                $aDtn=$aDta;}
            elseif($sPsw){
                $aDta["lgn"]=$sLgn;
                $aDta["psw"]=password_hash($sPsw,PASSWORD_DEFAULT);
                $aDta["ses"]=$sSes;
                $aDtn=$aDta;}
            if(($aDtn)and($rF=fopen(BDR.self::PTH.$sLgn,"w"))and($sDtn=serialize($aDtn))and($bWrt=fwrite($rF,$sDtn)))fclose($rF);}
        return ($bWrt)?$aDtn:null;
    }
}