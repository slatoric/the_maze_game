<?php
namespace core\lib\cls;
class MapCls
{
    private $aMap;
    private $aEntry;
    private $aExit;
    private $oUser;
    private $aSz;
    const UP=0,UPRT=1,RT=2,DNRT=3,DN=4,DNLT=5,LT=6,UPLT=7;
    function __construct(array $aMap){
        foreach($aMap as $iK=>$aLn){
            foreach($aLn as $iJ=>$iV){
                $aMapIn[]=new TileCls(($iV==1)?:false,false,["iX"=>$iJ,"iY"=>$iK]);
                $iX=($iJ>$iX)?$iJ:$iX;
            }
            $aNew[]=$aMapIn;unset($aMapIn);
            $iY=($iK>$iY)?$iK:$iY;
        }
        $this->aMap=$aNew;
        $this->aSz=["iX"=>$iX,"iY"=>$iY];
    }
    public function draw(){
        return MapPicCls4MapIfc::draw($this->aMap);
    }
    public function toggle($bShow){
        foreach($this->aMap as $iK=>$aLn){
            foreach($aLn as $iJ=>$oT){
                if(is_object($oT))
                    ($bShow)?$oT->show():$oT->hide();
            }
        }
    }
    public function get_entry(){
        foreach(end($this->aMap) as $iK=>$oT){//lower row
            if(!$oT->is_wall()){
                $oT->set_entry();
                $aEntry[]=$oT;}
        }
        $this->aEntry=$aEntry;
    }
    public function get_exit(){
        foreach($this->aMap[0] as $iK=>$oT){//upper row
            if(!$oT->is_wall()){
                $oT->set_exit();
                $aExit[]=$oT;}
        }
        $this->aExit=$aExit;
    }
    public function get_dir(array $aPos=null){
        if(!$aPos)$aPos=$this->get_user_pos();
        $oT=$this->get_tile($aPos);
        if($oT&&!$oT->is_wall()){
            $aDrs=[self::UP,self::RT,self::DN,self::LT];
            foreach($aDrs as $iDir)
                if(($oT=$this->get_tile($aPos,$iDir))and(!$oT->is_wall()))
                    $aDrsn[]=$iDir;
        }
        return $aDrsn;
    }
    public function get_next_pos($iDir,array $aPos=null,$bFrn=false){
        if(!$aPos)$aPos=$this->get_user_pos();
        $aDrs=$this->get_dir($aPos);
        $bGo=(($bFrn)or((!$bFrn)and(count($aDrs)<3)));
        if((isset($iDir))and($aDrs)and($bGo)and(in_array($iDir,$aDrs))){
            switch($iDir){
                case 0:
                    $aPsn=["iY"=>$aPos["iY"]-1,"iX"=>$aPos["iX"]];
                    break;
                case 2:
                    $aPsn=["iY"=>$aPos["iY"],"iX"=>$aPos["iX"]+1];
                    break;
                case 4:
                    $aPsn=["iY"=>$aPos["iY"]+1,"iX"=>$aPos["iX"]];
                    break;
                case 6:
                    $aPsn=["iY"=>$aPos["iY"],"iX"=>$aPos["iX"]-1];
                    break;
            }
            if($aPsn)$aPsr=$this->get_next_pos($iDir,$aPsn);
            if(!$aPsr)$aPsr=$aPsn;
        }
        else $aPsr=$aPos;
        return $aPsr;
    }
    public function set_user($iDir=null){
        if(isset($iDir)){
            extract($aPsw=$this->oUser->get_pos());
            $this->aMap[$iY][$iX]->toggle_user(false);
            $aPsn=$this->get_next_pos($iDir,$aPsw,true);
            $this->oUser->set_pos($aPsn);
            $this->aMap[$aPsn["iY"]][$aPsn["iX"]]->toggle_user(true);
            $this->recover_track($aPsw,$aPsn);
        }
        else{
            if(!$this->aEntry)$this->get_entry();
            foreach($this->aEntry as $oEntry){
                extract($aPos=$oEntry->get_pos());
                $this->aMap[$iY][$iX]->toggle_user(true);
                if(!$this->oUser)$this->oUser=new UserCls($aPos);
                else $this->oUser->set_pos($aPos);
                $this->recover_area($aPos);
                break;}
        }
    }
    public function recover($aPos){
        extract($aPos);
        if((isset($iY))and($oT=$this->aMap[$iY][$iX]))$oT->show();
    }
    public function recover_area($aPos){
        $this->recover($aPos);
        for($iDir=0;$iDir<=7;$iDir++)
            if($oT=$this->get_tile($aPos,$iDir))$this->recover($oT->get_pos());
    }
    public function recover_track($aPsw,$aPsn=null){
        $iYw=$aPsw["iY"];
        $iXw=$aPsw["iX"];
        if($aPsn)extract($aPsn);
        $bHor=($iYw==$iY)?:false;
        $bVer=($iXw==$iX)?:false;
        if(isset($iYw)){
            if((!$bHor&&!$bVer)or($bHor&&$bVer))$this->recover_area($aPsw);
            elseif($bHor&&!$bVer){
                $iDmx=($iXw>$iX)?$iXw:$iX;
                $iDmn=($iXw<$iX)?$iXw:$iX;}
            elseif(!$bHor&&$bVer){
                $iDmx=($iYw>$iY)?$iYw:$iY;
                $iDmn=($iYw<$iY)?$iYw:$iY;}
            if(isset($iDmx))
                for($iD=$iDmn;$iD<=$iDmx;$iD++){
                    $sX=($bHor)?"iD":"iXw";
                    $sY=($bHor)?"iYw":"iD";
                    $this->recover_area(["iY"=>$$sY,"iX"=>$$sX]);}
        }
    }
    public function get_user(){
        return $this->oUser;
    }
    public function get_user_pos(){
        return ($this->oUser)?$this->oUser->get_pos():null;
    }
    public function get_tile($aPos,$iDir=null){
        if(!$aPos)$aPos=$this->get_user_pos();
        if(isset($iDir)){
            switch($iDir){
                case 0:
                    if($iY=$aPos["iY"]){//row more than 0 so we can find upper
                        $iYn=$iY-1;
                        $iXn=$aPos["iX"];}
                    break;
                case 1:
                    if(($iY=$aPos["iY"])and($aPos["iX"]<$this->aSz["iX"])){//more than 0 and less than rightmost so we can find upperright
                        $iYn=$iY-1;
                        $iXn=$aPos["iX"]+1;}
                    break;
                case 2:
                    if($aPos["iX"]<$this->aSz["iX"]){//less than rightmost so we can find right
                        $iYn=$aPos["iY"];
                        $iXn=$aPos["iX"]+1;}
                    break;
                case 3:
                    if(($aPos["iY"]<$this->aSz["iY"])and($aPos["iX"]<$this->aSz["iX"])){//less than lowest and less than rightmost so we can find lowerright
                        $iYn=$aPos["iY"]+1;
                        $iXn=$aPos["iX"]+1;}
                    break;
                case 4:
                    if($aPos["iY"]<$this->aSz["iY"]){//less than lowest so we can find lower
                        $iYn=$aPos["iY"]+1;
                        $iXn=$aPos["iX"];}
                    break;
                case 5:
                    if(($aPos["iY"]<$this->aSz["iY"])and($iX=$aPos["iX"])){//less than lowest and column more than 0 so we can find lowerleft
                        $iYn=$aPos["iY"]+1;
                        $iXn=$iX-1;}
                    break;
                case 6:
                    if($iX=$aPos["iX"]){//column more than 0 so we can find left
                        $iYn=$aPos["iY"];
                        $iXn=$iX-1;}
                    break;
                case 7:
                    if(($iY=$aPos["iY"])and($iX=$aPos["iX"])){//row more than 0 and column more than 0 so we can find upperleft
                        $iYn=$iY-1;
                        $iXn=$iX-1;}
                    break;
                default:
                    $iYn=$iXn=null;
            }
        }
        else{
            $iYn=$aPos["iY"];
            $iXn=$aPos["iX"];}
        return (isset($iYn))?$this->aMap[$iYn][$iXn]:null;
    }
}