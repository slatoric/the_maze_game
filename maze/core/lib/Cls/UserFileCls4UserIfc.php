<?php
namespace core\lib\cls;
class UserFileCls4UserIfc implements \core\lib\ifc\UserIfc
{
    const PTH="/db/usr/";
    public static function get_user($sLgn,$sPsw,$sSes){
        if((($sLgn)and($sPsw||$sSes))and(file_exists($sFnm=BDR.self::PTH.$sLgn)))
            $aDta=unserialize(file_get_contents($sFnm));
        if(($aDta["psw"]==md5($sPsw))or($aDta["ses"]==$sSes))$aDtr=$aDta;
        return $aDtr;
    }
    public static function set_user($sLgn,$sPsw,$sSes,array $aDta){
        if(($sLgn)and($sPsw||$sSes)){
            if($mDtw=self::get_user($sLgn,$sPsw,$sSes)){
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