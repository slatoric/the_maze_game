<?php
namespace core\lib\cls;
class UserCls
{
    use \core\lib\trt\LngTrt;
    private static $sCls4UserIfc=__NAMESPACE__."\\".UserFileCls4UserIfc;
    private $aDta;
    function __construct(){
        
    }
    public static function show_frm_aut($bReg=false,array $aInf=null){
        $sMsg_hdr=($bReg)?self::t("Please, register"):self::t("Please, log in");
        $sMsg_lgn=self::t("Login");
        $sMsg_psw=self::t("Password");
        $sMsg_lgn_tip=self::t("Login must have from 3 to 10 english simbols");
        $sMsg_psw_tip=self::t("Password must have from 6 to 10 english simbols and/or digits");
        $sMsg_sub=($bReg)?self::t("Register"):self::t("Enter");
        $sLgn=self::log_in();
        $aTps=[$sMsg_lgn_tip,$sMsg_psw_tip];
        $aInf=($aInf)?array_merge($aInf,$aTps):$aTps;
        $sHtm="
            <form name='frm_usr' action='' method='post'>
                <div class='hdr'>{$sMsg_hdr}</div>
                <div class='opt'><input type='text' name='lgn' value='{$sLgn}' pattern='[A-Za-z]{3,10}' title='{$sMsg_lgn_tip}'><label for='lgn'>{$sMsg_lgn}</label></div>
                <div class='opt'><input type='password' name='psw' value='{$sPsw}' pattern='[A-Za-z\d]{6,10}' title='{$sMsg_psw_tip}'><label for='psw'>{$sMsg_psw}</label></div>
                <div class='sub'><input type='submit' name='aut' value='{$sMsg_sub}'></div>
            </form>
            <div class='mes_inf'>".implode("<br>",$aInf)."</div>";
        return $sHtm;
    }
    public static function show_settings(array $aInf=null){
        self::set_lng();
        $sMsg_hdr=self::t("Settings");
        $sMsg_lng=self::t("Language");
        $sLng_sel=self::show_sel("lng",self::$sLng);
        $sMsg_sub=self::t("Apply");
        $sHtm="
            <form name='frm_set' action='' method='post'>
                <div class='hdr'>{$sMsg_hdr}</div>
                <div class='opt'>{$sLng_sel}<label for='lgn'>{$sMsg_lng}</label></div>
                <div class='sub'><input type='submit' name='set' value='{$sMsg_sub}'></div>
            </form>";
        return $sHtm;
    }
    public static function chk_usrs(){
        $oUsr=new UserCls();
        $aDta=$oUsr->get_data();
        return $sLgn;
    }
    public static function log_in(){
        $sLgn=(isset($_REQUEST["lgn"]))?$_REQUEST["lgn"]:$_SESSION["lgn"];
        if(($sLgn)and((!$_SESSION["lgn"])or($_SESSION["lgn"]!=$sLgn)))$_SESSION["lgn"]=$sLgn;
        return $sLgn;
    }
    public static function log_out(){
        unset($_SESSION["lgn"]);
        $bLgo=(!$_SESSION["lgn"])?:false;
        return $bLgo;
    }
    public static function is_usr_dta($sLgn){
        try{
            if(!class_exists($sCls4UserIfc=self::$sCls4UserIfc))throw new ExcCls("No class for user interface",ExcCls::DEBUG);
            if(!$sLgn)throw new ExcCls("No login",ExcCls::DEBUG);
            $bDta=$sCls4UserIfc::is_usr_dta($sLgn);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bDta;}
    }
    public static function del_usr_dta($sLgn){
        try{
            if(!class_exists($sCls4UserIfc=self::$sCls4UserIfc))throw new ExcCls("No class for user interface",ExcCls::DEBUG);
            if(!$sCls4UserIfc::is_usr_dta($sLgn))throw new ExcCls("No match login",ExcCls::DEBUG);
            if(!$bDel=$sCls4UserIfc::del_usr_dta($sLgn))throw new ExcCls("No delete login data",ExcCls::DEBUG);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bDel;}
    }
    public function get_usr_dta($sLgn,$sPsw=null){//read from user object or file
        try{
            if($aDta=$this->aDta)$aDtr=$aDta;
            else{
                if(!class_exists($sCls4UserIfc=self::$sCls4UserIfc))throw new ExcCls("No class for user interface",ExcCls::DEBUG);
                if(!self::is_usr_dta($sLgn))throw new ExcCls("No match login",ExcCls::DEBUG);
                if((!$sPsw)and(!$sSid=session_id()))throw new ExcCls("No password or session",ExcCls::DEBUG);
                if(!$aDta=$sCls4UserIfc::get_usr_dta($sLgn))throw new ExcCls("No read login data",ExcCls::DEBUG);
                if($sPsw&&!password_verify($sPsw,$aDta["psw"]))throw new ExcCls("No match password",ExcCls::DEBUG);
                if((!$sPsw)and($aDta["ses"]!=$sSid))throw new ExcCls("No match session",ExcCls::DEBUG);
                $this->aDta=$aDtr=$aDta;}
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aDtr;}
    }
    public function set_usr_dta($sLgn,$sPsw=null,array $aDta=null){
        try{
            if(!class_exists($sCls4UserIfc=self::$sCls4UserIfc))throw new ExcCls("No class for user interface",ExcCls::DEBUG);
            if(!$sLgn)throw new ExcCls("No login",ExcCls::DEBUG);
            if(!$sSid=session_id())throw new ExcCls("No session",ExcCls::DEBUG);
            if(!$aDta)throw new ExcCls("No login data",ExcCls::DEBUG);
            if(!$sPsh=$aDta["psw"])throw new ExcCls("No password hash",ExcCls::DEBUG);
            if($sPsw&&!password_verify($sPsw,$sPsh))throw new ExcCls("No match password",ExcCls::DEBUG);
            if(!$aDta["dt_reg"])throw new ExcCls("No registration date",ExcCls::DEBUG);
            if($sPswn=$aDta["new_psw"]){
                if(!$sPsw)throw new ExcCls("No password",ExcCls::DEBUG);
                $aDta["psw"]=password_hash($sPswn,PASSWORD_DEFAULT);}
            $aDta["lgn"]=$sLgn;
            $aDta["ses"]=$sSid;
            $aDta["dt_upd"]=date("Y-m-d H:i:s");
            if(!$bWrt=$sCls4UserIfc::set_usr_dta($sLgn,$aDta))throw new ExcCls("No write login data",ExcCls::DEBUG);
            $this->aDta=$aDtr=$aDta;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aDtr;}
    }
    public function is_map(){
        return ($this->aDta["map"])?:false;
    }
    public function play($bGmn=true,$sDir=null){
        try{
            //echo "<pre>bGmn";var_dump($bGmn);echo "</pre>";
            //echo "<pre>sDir";var_dump($sDir);echo "</pre>";
            //echo "<pre>this->aDta";var_dump($this->aDta);echo "</pre>";
            if($bGmn){
                $this->aDta["map"]=$oMap=MapCls::get_map(1);
                //$this->aDta=$this->set_usr_dta($this->aDta["lgn"],null,$this->aDta);
            }elseif($this->aDta["map"])
                $oMap=$this->aDta["map"];
            //echo "<pre>oMap";var_dump($oMap);echo "</pre>";
            //exit;
            //$bUsr=$oMap->is_win();
            //echo "<pre>bUsr";var_dump($bUsr);echo "</pre>";
            if(!is_object($oMap))throw new ExcCls("No map",ExcCls::DEBUG);
            switch($sDir){
                case "/\\":
                    $iDir=MapCls::UP;
                    break;
                case "\/":
                    $iDir=MapCls::DN;
                    break;
                case "<":
                    $iDir=MapCls::LT;
                    break;
                case ">":
                    $iDir=MapCls::RT;
                    break;
                default:
                    unset($iDir);
            }
            if(isset($iDir))$oMap->set_usr($iDir);
            if($oMap->is_win()){
                $sHtm="<div class='mes_inf'>".self::t("You win!")."</div>";
            }else{
                $this->aDta=$this->set_usr_dta($this->aDta["lgn"],null,$this->aDta);
                $sHtm="<br>".$oMap->show_map();
                $sHtm.=$oMap->show_joy();
            }
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sHtm;}
    }
}