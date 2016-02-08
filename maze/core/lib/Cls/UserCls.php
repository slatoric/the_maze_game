<?php
namespace core\lib\cls;
class UserCls
{
    use \core\lib\trt\LngTrt;
    private static $sCls4UserIfc=__NAMESPACE__."\\".UserFileCls4UserIfc;
    private $aPos;
    private $aHis;//history
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
                <div class='opt'><input type='text' name='lgn' value='{$sLgn}' pattern='[A-Za-z]{3,10}' alt='{$sMsg_lgn_tip}'><label for='lgn'>{$sMsg_lgn}</label></div>
                <div class='opt'><input type='password' name='psw' value='{$sPsw}' pattern='[A-Za-z\d]{6,10}' alt='{$sMsg_psw_tip}'><label for='psw'>{$sMsg_psw}</label></div>
                <div class='sub'><input type='submit' name='aut' value='{$sMsg_sub}'></div>
            </form>
            <div class='mes_inf'>".implode("<br>",$aInf)."</div>";
        return $sHtm;
    }
    public static function check_users(){
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
    public static function is_user_data($sLgn){
        try{
            if(!class_exists($sCls4UserIfc=self::$sCls4UserIfc))throw new ExcCls("No class for user interface",ExcCls::DEBUG);
            if(!$sLgn)throw new ExcCls("No login",ExcCls::DEBUG);
            $bDta=$sCls4UserIfc::is_user_data($sLgn);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bDta;}
    }
    public function get_user_data($sLgn,$sPsw=null){
        try{
            if($aDta=$this->aDta)$aDtr=$aDta;
            else{
                if(!class_exists($sCls4UserIfc=self::$sCls4UserIfc))throw new ExcCls("No class for user interface",ExcCls::DEBUG);
                if(!$this->is_user_data($sLgn))throw new ExcCls("No match login",ExcCls::DEBUG);
                if((!$sPsw)and(!$sSid=session_id()))throw new ExcCls("No password or session",ExcCls::DEBUG);
                if(!$aDta=$sCls4UserIfc::get_user_data($sLgn))throw new ExcCls("No read login data",ExcCls::DEBUG);
                if($sPsw&&!password_verify($sPsw,$aDta["psw"]))throw new ExcCls("No match password",ExcCls::DEBUG);
                if((!$sPsw)and($aDta["ses"]!=$sSid))throw new ExcCls("No match session",ExcCls::DEBUG);
                $this->aDta=$aDtr=$aDta;}
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aDtr;}
    }
    public function set_user_data($sLgn,$sPsw=null,array $aDta=null){
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
            if(!$bWrt=$sCls4UserIfc::set_user_data($sLgn,$aDta))throw new ExcCls("No write login data",ExcCls::DEBUG);
            $this->aDta=$aDtr=$aDta;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aDtr;}
    }
    public static function del_user_data($sLgn){
        try{
            if(!class_exists($sCls4UserIfc=self::$sCls4UserIfc))throw new ExcCls("No class for user interface",ExcCls::DEBUG);
            if(!$sCls4UserIfc::is_user_data($sLgn))throw new ExcCls("No match login",ExcCls::DEBUG);
            if(!$bDel=$sCls4UserIfc::del_user_data($sLgn))throw new ExcCls("No delete login data",ExcCls::DEBUG);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bDel;}
    }
    public function get_pos(){
        return $this->aPos;
    }
    public function set_pos(array $aPos){
        $this->aPos=$aPos;
        $this->aHis[]=$aPos;
    }
}