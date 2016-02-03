<?php
namespace lib\cls;
class MazeCls
{
    function __construct(){
        
    }
    public function t($sMsg){
        return LngFileCls4LngIfc::t($sMsg);
    }
    public function show_hi(){
        $sHtm=$this->t("Welcome to The Maze Game, User!");
        return $sHtm;
    }
    public function set_user(){
        $oUsr=new UserCls();
        echo "<pre>oUsr";var_dump($oUsr);echo "</pre>";
        return $sHtm;
    }
}