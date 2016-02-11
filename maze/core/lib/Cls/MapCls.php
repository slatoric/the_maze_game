<?php
namespace core\lib\cls;
class MapCls
{
    private $aMap;
    private $aEnt;
    private $aExt;
    private $aSz;
    const UP=0,UPRT=1,RT=2,DNRT=3,DN=4,DNLT=5,LT=6,UPLT=7;
    function __construct(array $aMap){
        try{
            if(!$aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            foreach($aMap as $iK=>$aLn){
                foreach($aLn as $iJ=>$iV){
                    $aLnn[]=new TileCls(($iV==1)?:false,false,["iX"=>$iJ,"iY"=>$iK]);
                    $iX=($iJ>$iX)?$iJ:$iX;//max horiz
                }
                $aMpn[]=$aLnn;unset($aLnn);
                $iY=($iK>$iY)?$iK:$iY;//max vert
            }
            $this->aMap=$aMpn;
            $this->aSz=["iX"=>$iX,"iY"=>$iY];
            $bR=true;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public function show_map(){
        return MapPicCls4MapIfc::show_map($this->aMap);
    }
    public static function chk_pos($aPos){
        try{
            if(!$aPos)throw new ExcCls("No position",ExcCls::DEBUG);
            if(!isset($aPos["iY"])&&!isset($aPos["iX"]))throw new ExcCls("No match position",ExcCls::DEBUG);
            $aPsr=$aPos;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aPsr;}
    }
    public function set_vis($bVis=false){
        try{
            if(!$this->aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            $bR=true;
            foreach($this->aMap as $iK=>$aLn){
                foreach($aLn as $iJ=>$oT){
                    if(!is_object($oT))throw new ExcCls("No tile",ExcCls::DEBUG);
                    if(!$oT->set_vis($bVis))$bR=false;
                }
            }
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public function get_ent(){
        try{
            if(!$this->aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            foreach(end($this->aMap) as $iK=>$oT){//entry can be only in lower row
                if(!is_object($oT))throw new ExcCls("No tile",ExcCls::DEBUG);
                if($oT->set_ent($bVis))$aEnt[]=$oT;
            }
            $this->aEnt=$aEnt;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $this->aEnt;}
    }
    public function get_ext(){
        try{
            if(!$this->aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            foreach(reset($this->aMap) as $iK=>$oT){//exit can be only in upper row
                if(!is_object($oT))throw new ExcCls("No tile",ExcCls::DEBUG);
                if($oT->set_ext($bVis))$aExt[]=$oT;
            }
            $this->aExt=$aExt;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $this->aExt;}
    }
    public function get_dir(array $aPos){//finds possible ways on tile
        try{
            $oT=$this->get_tile($aPos);
            if(is_object($oT)&&!$oT->is_wll()){
                foreach([self::UP,self::RT,self::DN,self::LT] as $iDir)
                    if((is_object($oT=$this->get_tile($aPos,$iDir)))and(!$oT->is_wll()))
                        $aDrs[]=$iDir;
            }
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aDrs;}
    }
    public function get_pos_nxt($iDir,array $aPos=null,$bFrn=false){//recursive
        try{
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
                if($aPsn)$aPsr=$this->get_pos_nxt($iDir,$aPsn);
                if(!$aPsr)$aPsr=$aPsn;
            }else
                $aPsr=$aPos;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aPsr;}
    }
    public function set_usr($iDir=null,&$oUsr=null){//may be only map <--> user interaction privider
        try{
            if(!$aMap=$this->aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            if($oUsr&&!is_object($oUsr))throw new ExcCls("No match user",ExcCls::DEBUG);
            if(isset($iDir)){
                if(!$aPsw=self::chk_pos($oUsr->get_pos()))throw new ExcCls("No user position",ExcCls::DEBUG);
                extract($aPsw);
                if(!$aMap[$iY][$iX]->set_usr(false))throw new ExcCls("No unset user tile",ExcCls::DEBUG);
                $aPsn=$this->get_pos_nxt($iDir,$aPsw,true);
                if(!$aMap[$aPsn["iY"]][$aPsn["iX"]]->set_usr(true))throw new ExcCls("No set user tile",ExcCls::DEBUG);
                if(!$oUsr->set_pos($aPsn))throw new ExcCls("No set user position",ExcCls::DEBUG);
                if(!$this->recover_track($aPsw,$aPsn))throw new ExcCls("No recover track",ExcCls::DEBUG);
            }else{
                if(!$aEnt=$this->aEnt)throw new ExcCls("No entries",ExcCls::DEBUG);
                if(!is_object($oT=reset($aEnt)))throw new ExcCls("No entry tile",ExcCls::DEBUG);
                if(!$aPos=self::chk_pos($oT->get_pos()))throw new ExcCls("No entry postion",ExcCls::DEBUG);
                extract($aPos);
                if(!$aMap[$iY][$iX]->set_usr(true))throw new ExcCls("No set user tile",ExcCls::DEBUG);
                if(!$oUsr->set_pos($aPos))throw new ExcCls("No set user position",ExcCls::DEBUG);
                if(!$this->recover_area($aPos))throw new ExcCls("No recover area",ExcCls::DEBUG);
            }
            $bR=true;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public function recover(array $aPos){
        try{
            if(!$aMap=$this->aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            if(!$aPos=self::chk_pos($aPos))throw new ExcCls("No position",ExcCls::DEBUG);
            extract($aPos);
            if(!is_object($oT=$aMap[$iY][$iX]))throw new ExcCls("No tile",ExcCls::DEBUG);
            $bR=$oT->set_vis(true);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public function recover_area(array $aPos){
        try{
            if(!$aPos=self::chk_pos($aPos))throw new ExcCls("No position",ExcCls::DEBUG);
            if(!$bR=$this->recover($aPos))throw new ExcCls("No recover tile",ExcCls::DEBUG);
            for($iDir=0;$iDir<=7;$iDir++){
                if(is_object($oT=$this->get_tile($aPos,$iDir))){//no throw exception
                    if(!$aPsc=self::chk_pos($oT->get_pos()))throw new ExcCls("No position",ExcCls::DEBUG);
                    if(!$this->recover($aPsc))$bR=false;
                }
            }
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public function recover_track(array $aPsw,array $aPsn=null){
        try{
            $iYw=$aPsw["iY"];
            $iXw=$aPsw["iX"];
            if($aPsn)
                extract($aPsn);
            $bHor=($iYw==$iY)?:false;
            $bVer=($iXw==$iX)?:false;
            if(isset($iYw)){
                if((!$bHor&&!$bVer)or($bHor&&$bVer))//if no vert and horiz shift or both vert and horiz shift
                    $bR=$this->recover_area($aPsw);
                elseif($bHor&&!$bVer){//if horiz shift
                    $iDmx=($iXw>$iX)?$iXw:$iX;
                    $iDmn=($iXw<$iX)?$iXw:$iX;}
                elseif(!$bHor&&$bVer){//if vert shift
                    $iDmx=($iYw>$iY)?$iYw:$iY;
                    $iDmn=($iYw<$iY)?$iYw:$iY;}
                if(isset($iDmx))
                    for($iD=$iDmn;$iD<=$iDmx;$iD++){
                        $sX=($bHor)?"iD":"iXw";
                        $sY=($bHor)?"iYw":"iD";
                        if(!$this->recover_area(["iY"=>$$sY,"iX"=>$$sX]))
                            $bR=false;
                    }
            }
            $bR=($bR===false)?false:true;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public function get_tile(array $aPos,$iDir=null){
        try{
            if(!$aPos=self::chk_pos($aPos))throw new ExcCls("No position",ExcCls::DEBUG);
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
            }else{
                $iYn=$aPos["iY"];
                $iXn=$aPos["iX"];
            }
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return (isset($iYn))?$this->aMap[$iYn][$iXn]:null;}
    }
}