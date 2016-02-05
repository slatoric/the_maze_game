<?php
namespace lib\cls;
class MazeCls
{
    private $oMap;
    private $oUser;
    function __construct(){
        
    }
    public function t($sMsg){
        return LngFileCls4LngIfc::t($sMsg);
    }
    public function say_hi(){
        $sHtm=$this->t("Welcome to The Maze Game, User!");
        return $sHtm;
    }
    
    public function set_user(){
        //if(get_user())
        if(!$this->oUser)$this->oUser=new UserCls();
        echo $this->oUser->show_form();
        $aDta=$this->oUser->get_data();
        echo "<pre>aDta";var_dump($aDta);echo "</pre>";
        //$bWrt=$this->oUser->set_data(["boo"=>"boo"]);
        //echo "<pre>bWrt";var_dump($bWrt);echo "</pre>";
        //$bLgo=$this->oUser->log_out();
        //echo "<pre>bLgo";var_dump($bLgo);echo "</pre>";
        //$bWrt=$this->oUser->set_data(["goo"=>"koo"]);
        //echo "<pre>bWrt";var_dump($bWrt);echo "</pre>";
        return $sHtm;
    }
    public function get_user(){
        
        return $oUsr;
    }
}