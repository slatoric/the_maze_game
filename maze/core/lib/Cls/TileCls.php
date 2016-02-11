<?php
namespace core\lib\cls;
class TileCls
{
    private $aPos;
    private $bWll;
    private $bVis=false;
    private $bEnt=false;
    private $bExt=false;
    private $bUsr=false;
    function __construct($bWll=false,$bVis=false,array $aPos=null){
        try{
            if(!$aPos)throw new ExcCls("No position",ExcCls::DEBUG);
            $this->bWll=($bWll)?:false;
            $this->bVis=($bVis)?:false;
            $this->aPos=$aPos;
            $bR=true;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public function get_pos(){
        return $this->aPos;
    }
    public function is_vis(){
        return $this->bVis;
    }
    public function is_wll(){
        return $this->bWll;
    }
    public function is_ent(){
        return $this->bEnt;
    }
    public function is_ext(){
        return $this->bExt;
    }
    public function is_usr(){
        return $this->bUsr;
    }
    public function set_vis($bVis=false){
        $this->bVis=$bVis;
        return ($this->bVis===$bVis)?:false;
    }
    public function set_usr($bUsr=false){
        try{
            if($this->bWll&&$bUsr)throw new ExcCls("No wall can have user",ExcCls::DEBUG);
            $this->bUsr=$bUsr;
            $bR=($this->bUsr===$bUsr)?:false;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public function set_ent(){
        try{
            if($this->bWll)throw new ExcCls("No wall can be entry",ExcCls::DEBUG);
            $this->bEnt=true;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $this->bEnt;}
    }
    public function set_ext(){
        try{
            if($this->bWll)throw new ExcCls("No wall can be exit",ExcCls::DEBUG);
            $this->bExt=true;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $this->bExt;}
    }
}