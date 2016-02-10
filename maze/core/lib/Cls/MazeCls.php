<?php
namespace core\lib\cls;
class MazeCls
{
    use \core\lib\trt\LngTrt;//to send multilingual messages to user
    private $oMap;
    private $oUsr;
    function __construct(){
        
    }
    public function run(){
        self::set_lng();
        if($_REQUEST["do"]){
            if($_REQUEST["act"]=="ext"){
                if($bLgo=UserCls::log_out())
                    $aInf[]=self::t("User successfully exited");
            }elseif($_REQUEST["act"]=="gmn"){
                $bGmn=true;
            }elseif($_REQUEST["act"]=="gmr"){
                echo "old game";
            }elseif($_REQUEST["act"]=="set"){
                $sHtm=UserCls::show_settings();
            }else{
                $aInfm[]=self::t("Please, select action");
            }
        }
        echo $this->init_user($aInf);
        if($bUsr=$this->is_user()){
            if($bGmn)echo $this->new_map();
            else echo ($sHtm)?:self::show_menu_main($aInfm);
            
            }
            
        else
            echo $sSel=self::show_lng_sel();//language selector
    }
    public function init_user(array $aInf=null){
        $oUsr=new UserCls();
        UserCls::set_lng(self::$sLng);
        $sLgn=UserCls::log_in();
        if($sAut=$_REQUEST["aut"]){
            if(!$sLgn){
                $aErr[]=self::t('No login');
                $bFmd=false;//enter mode
            }else{
                if(!UserCls::is_user_data($sLgn)and($sAut==self::t("Enter"))){
                    $aErr[]=self::t('No match login');
                    $bFmd=true;//register mode
                }elseif((!$sPsw=$_REQUEST["psw"])or((UserCls::is_user_data($sLgn))and(!$aDta=$oUsr->get_usr_dta($sLgn,$sPsw)))){//empty or wrong pass
                    $aErr[]=self::t('No match password');
                    $bFmd=(($sAut!=self::t("Enter"))&&(!UserCls::is_user_data($sLgn)))?:false;//check mode
                }
            }
        }
        if(!$aErr){
            if($sAut==self::t("Register"))$aDta=$oUsr->set_user_data($sLgn,$sPsw=$_REQUEST["psw"],["dt_reg"=>date("Y-m-d H:i:s"),"psw"=>password_hash($sPsw,PASSWORD_DEFAULT)]);
            else $aDta=$oUsr->get_usr_dta($sLgn,$sPsw);
        }
        $sHtm=self::say_hi($aDta["lgn"]);
        if($aErr)$sHtm.="<div class='mes_err'>".implode("<br>",$aErr)."</div>";
        if(!$aDta&&!isset($bFmd))$bFmd=false;//enter mode by default
        if(isset($bFmd))
            $sHtm.=UserCls::show_frm_aut($bFmd,$aInf);
        if(($aDta)and(!$this->oUsr)){
            if($aDta["ses"]!=$sSid=session_id())$aDta=$oUsr->set_user_data($sLgn,"",$aDta);
            $this->oUsr=$oUsr;}
        return $sHtm;
    }
    public function is_user(){
        return ($this->oUsr)?true:false;
    }
    public static function say_hi($sLgn=null){
        $sLgn=($sLgn)?:self::t("User");
        $sHtm=self::t("Welcome to The Maze Game").", $sLgn!";
        return $sHtm;
    }
    public static function show_lng_sel(){
        if($sSel=self::show_sel("lng",self::set_lng())){
            $sMsg_lng=self::t("Language");
            $sMsg_sub=self::t("Change");
            $sHtm="
                <form name='frm_lng' action='' method='post'>
                    <div class='opt sub'>{$sSel}<label for='gmn'>{$sMsg_gmn}</label><input type='submit' name='lng_cng' value='{$sMsg_sub}'></div>
                </form>";
            }
        return $sHtm;
    }
    public static function show_menu_main(array $aInf=null){
        $sMsg_hdr=self::t("Main menu");
        $sMsg_gmn=self::t("Play new game");
        $sMsg_gmr=self::t("Resume saved game");
        $sMsg_set=self::t("Customize");
        $sMsg_ext=self::t("Exit");
        $sMsg_sub=self::t("Apply");
        $aTps=[$sMsg_lgn_tip,$sMsg_psw_tip];
        $sInf=($aInf)?"<div class='mes_inf'>".implode("<br>",$aInf)."</div>":"";
        $sHtm="
            <form name='frm_mnu_mn' action='' method='post'>
                <div class='hdr'>{$sMsg_hdr}</div>
                <div class='opt'><input type='radio' name='act' id='gmn' value='gmn'><label for='gmn'>{$sMsg_gmn}</label></div>
                <div class='opt'><input type='radio' name='act' id='gmr' value='gmr'><label for='gmr'>{$sMsg_gmr}</label></div>
                <div class='opt'><input type='radio' name='act' id='set' value='set'><label for='set'>{$sMsg_set}</label></div>
                <div class='opt'><input type='radio' name='act' id='ext' value='ext'><label for='ext'>{$sMsg_ext}</label></div>
                <div class='sub'><input type='submit' name='do' value='{$sMsg_sub}'></div>
            </form>{$sInf}";
        return $sHtm;
    }
    public static function show_menu_game(){
        $sMsg_hdr=self::t("Game menu");
        $sMsg_sub=self::t("Main menu");
        $sHtm="
            <form name='frm_mnu_gm' action='' method='post'>
                <div class='hdr'>{$sMsg_hdr}</div>
                <div class='sub'><input type='submit' name='2mn' value='{$sMsg_sub}'></div>
            </form>";
        return $sHtm;
    }
    public function new_map(){
        $aMap=[
[1,0,1,1,1,1,1,1,1],
[1,0,0,0,0,0,0,0,1],
[1,1,1,1,1,1,1,0,1],
[1,0,0,0,0,0,0,0,1],
[1,0,1,1,0,1,1,1,1],
[1,0,0,1,0,0,0,0,1],
[1,1,1,1,1,1,1,0,1],
[1,0,0,0,0,0,0,0,1],
[1,1,1,1,0,1,1,1,1],
];
        $oMap=new MapCls($aMap);
        //echo "<pre>";var_dump($oMap);echo "</pre>";
        $bVis=$oMap->set_vis(false);
        //echo "<pre>bVis";var_dump($bVis);echo "</pre>";
        $aEnt=$oMap->get_ent();
        //echo "<pre>aEnt";var_dump($aEnt);echo "</pre>";
        $aExt=$oMap->get_ext();
        //echo "<pre>aExt";var_dump($aExt);echo "</pre>";
        $bUsr=$oMap->set_usr($this->oUsr);
        //echo "<pre>bUsr";var_dump($bUsr);echo "</pre>";
        $sHtm="<br>".$oMap->show_map();
        //echo "<pre>oMap";var_dump($oMap);echo "</pre>";
        
        $bUsr=$oMap->set_usr($this->oUsr,MapCls::UP);
        echo "<pre>bUsr";var_dump($bUsr);echo "</pre>";
        $sHtm="<br>".$oMap->show_map();
        exit;
        $oJoy=new JoysCls([0,2,4,6]);
        //echo "<pre>oJoy";var_dump($oJoy);echo "</pre>";
        $sHtm.=$oJoy->show_joy();
        //echo $sHtm;
        echo $oJoy->get_dir();
        return $sHtm;
    }
}