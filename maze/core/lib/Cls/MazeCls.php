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
                <div class='opt'><input type='radio' name='act' id='gnm' value='gnm'><label for='gnm'>{$sMsg_gmn}</label></div>
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
    public function man_user(){
        if(!$this->oUser)$this->oUser=new UserCls();
        if(!$aDta=$this->oUser->get_user()){
            if($_REQUEST["aut"])$aDta=$this->oUser->set_user(["dta_reg"=>date("Y-m-d H:i:s")]);
            else $sAut=$this->oUser->show_frm_aut();}
        $sHtm=$this->say_hi($aDta["lgn"]).$sAut;
        if($aDta)$sHtm.=$this->show_menu_main();
        return $sHtm;
    }
    public function get_user(){
        
        return $oUsr;
    }
}