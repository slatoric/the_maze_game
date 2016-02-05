<?php
namespace core\lib\cls;
class TileCls
{
    private $aPos;
    private $bWall;
    private $bVis;
    private $bEntry=false;
    private $bExit=false;
    private $bUser=false;
    function __construct($bIsWall,$bIsVis,$aPos){
        $this->bWall=($bIsWall)?:false;
        $this->bVis=($bIsVis)?:false;
        $this->aPos=$aPos;
    }
    public function hide(){
        $this->bVis=false;
    }
    public function show(){
        $this->bVis=true;
    }
    public function is_wall(){
        return $this->bWall;
    }
    public function is_visible(){
        return $this->bVis;
    }
    public function is_entry(){
        return $this->bEntry;
    }
    public function set_entry(){
        $this->bEntry=true;
    }
    public function is_exit(){
        return $this->bExit;
    }
    public function set_exit(){
        $this->bExit=true;
    }
    public function is_user(){
        return $this->bUser;
    }
    public function toggle_user($bUser){
        if(!$this->bWall)
            $this->bUser=$bUser;
    }
    public function get_pos(){
        return $this->aPos;
    }
}