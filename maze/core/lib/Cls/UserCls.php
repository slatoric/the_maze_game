<?php
namespace core\lib\cls;
class UserCls
{
    private $aPos;
    private $aHis;//history
    private $aDta;
    function __construct(){
        
    }
    public function t($sMsg){
        return LngFileCls4LngIfc::t($sMsg);
    }
    public function show_frm_aut(){
        $sMsg_hdr=$this->t("Please, log in to proceed.");
        $sMsg_lgn=$this->t("Login");
        $sMsg_psw=$this->t("Password");
        $sMsg_sub=$this->t("Enter");
        $sHtm="
            <form name='frm_usr' action='' method='post'>
                <div class='hdr'>{$sMsg_hdr}</div>
                <div class='opt'><input type='text' name='lgn' value='{$sLgn}'><label for='lgn'>{$sMsg_lgn}</label></div>
                <div class='opt'><input type='password' name='psw' value='{$sPsw}'><label for='psw'>{$sMsg_psw}</label></div>
                <div class='sub'><input type='submit' name='aut' value='{$sMsg_sub}'></div>
            </form>";
        return $sHtm;
    }
    public static function check_users(){
        $oUsr=new UserCls();
        $aDta=$oUsr->get_data();
        return $sLgn;
    }
    public function log_in(){
        $sLgn=($_REQUEST["lgn"])?:$_SESSION["lgn"];
        if($sLgn&&!$_SESSION["lgn"])$_SESSION["lgn"]=$sLgn;
        return $sLgn;
    }
    public function log_out(){
        unset($_SESSION["lgn"]);
        $bLgo=(!$_SESSION["lgn"])?:false;
        return $bLgo;
    }
    public function get_user(){
        return ($this->aDta)?:$this->aDta=UserFileCls4UserIfc::get_user($this->log_in(),$_REQUEST["psw"],session_id());
    }
    public function set_user(array $aDta){
        return $this->aDta=UserFileCls4UserIfc::set_user($this->log_in(),$_REQUEST["psw"],session_id(),$aDta);
    }
    public function get_pos(){
        return $this->aPos;
    }
    public function set_pos(array $aPos){
        $this->aPos=$aPos;
        $this->aHis[]=$aPos;
    }
}