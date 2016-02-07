<?php
namespace core\lib\cls;
class MazeCls
{
    private $oMap;
    private $oUser;
    function __construct(){
        
    }
    public function t($sMsg){
        return LngFileCls4LngIfc::t($sMsg);
    }
    public function run(){
        $sHtm=$this->init_user();
        if($bUsr=$this->is_user()){
            if($_REQUEST["do"]){
                if($_REQUEST["act"]=="ext"){
                    $this->oUser->log_out();
                    unset($this->oUser);
                    $sHtm=$this->init_user();
                }elseif($_REQUEST["act"]=="gmn"){
                    echo "new game";
                }elseif($_REQUEST["act"]=="gmr"){
                    echo "old game";
                }elseif($_REQUEST["act"]=="set"){
                    echo "settings";
                }
            }
        }
        echo $sHtm;
        if($bUsr=$this->is_user())echo $this->show_menu_main();
    }
    public function say_hi($sLgn=null){
        $sLgn=($sLgn)?:$this->t("User");
        $sHtm=$this->t("Welcome to The Maze Game").", $sLgn!";
        return $sHtm;
    }
    public function show_menu_main(){
        $sMsg_hdr=$this->t("Main menu");
        $sMsg_gmn=$this->t("Play new game");
        $sMsg_gmr=$this->t("Resume saved game");
        $sMsg_set=$this->t("Customize");
        $sMsg_ext=$this->t("Exit");
        $sMsg_sub=$this->t("Apply");
        $sHtm="
            <form name='frm_mnu_mn' action='' method='post'>
                <div class='hdr'>{$sMsg_hdr}</div>
                <div class='opt'><input type='radio' name='act' id='gmn' value='gmn'><label for='gmn'>{$sMsg_gmn}</label></div>
                <div class='opt'><input type='radio' name='act' id='gmr' value='gmr'><label for='gmr'>{$sMsg_gmr}</label></div>
                <div class='opt'><input type='radio' name='act' id='set' value='set'><label for='set'>{$sMsg_set}</label></div>
                <div class='opt'><input type='radio' name='act' id='ext' value='ext'><label for='ext'>{$sMsg_ext}</label></div>
                <div class='sub'><input type='submit' name='do' value='{$sMsg_sub}'></div>
            </form>";
        return $sHtm;
    }
    public function show_menu_game(){
        $sMsg_hdr=$this->t("Game menu");
        $sMsg_sub=$this->t("Main menu");
        $sHtm="
            <form name='frm_mnu_gm' action='' method='post'>
                <div class='hdr'>{$sMsg_hdr}</div>
                <div class='sub'><input type='submit' name='2mn' value='{$sMsg_sub}'></div>
            </form>";
        return $sHtm;
    }
    public function init_user(){
        //2do: mes_err empty pass
        $oUsr=new UserCls();
        if(($sLgn=$oUsr->log_in())and($oUsr->is_user_data($sLgn))){
            if(!$aDta=$oUsr->get_user_data($sLgn,$_REQUEST["psw"]))
                $sAdd="<div class='mes_err'>{$this->t('No match password')}</div>{$oUsr->show_frm_aut()}";
        }else{
            if($_REQUEST["sub"]==$this->t("Register"))
                $aDta=$oUsr->set_user_data($sLgn,$sPsw=$_REQUEST["psw"],["dt_reg"=>date("Y-m-d H:i:s"),"psw"=>password_hash($sPsw,PASSWORD_DEFAULT)]);
            else{
                $sMes=($sLgn)?"<div class='mes_err'>{$this->t('No match login')}</div>":"";
                $sAdd=$sMes.$oUsr->show_frm_aut(($sLgn)?:false);}
        }
        $sHtm=$this->say_hi($aDta["lgn"]).$sAdd;
        if(($aDta)and(!$this->oUsr)){
            if($aDta["ses"]!=$sSid=session_id())$aDta=$oUsr->set_user_data($sLgn,"",$aDta);
            $this->oUser=$oUsr;}
        return $sHtm;
    }
    public function is_user(){
        return ($this->oUser)?true:false;
    }
}