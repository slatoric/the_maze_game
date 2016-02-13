<?php
namespace core\lib\cls;
class MapCls
{
    const PTH="/core/lib/lvl/";
    private $mMp;//map id
    private $sNm;
    private $sDsc;
    private $aMap;
    private $aEnt;
    private $aExt;
    private $aSz;
    private $aHis;//history of user position
    private $oJoy;
    const UP=0,UPRT=1,RT=2,DNRT=3,DN=4,DNLT=5,LT=6,UPLT=7;
    function __construct(array $aLvl){
        try{
            if(!$aLvl)throw new ExcCls("No map array",ExcCls::DEBUG);
            if(!$sNm=$aLvl["nm"])throw new ExcCls("No map name",ExcCls::DEBUG);
            if(!$aMap=$aLvl["map"])throw new ExcCls("No map",ExcCls::DEBUG);
            $this->sNm=$sNm;
            $this->sDsc=$aLvl["dsc"];
            $this->mMp=$aLvl["mmp"];
            if(isset($aLvl["way"])&&is_int($aLvl["way"]))$iWay=$aLvl["way"];
            if(isset($aLvl["wll"])&&is_int($aLvl["wll"]))$iWll=$aLvl["wll"];
            if(isset($aLvl["ent"])&&is_int($aLvl["ent"]))$iEnt=$aLvl["ent"];
            if(isset($aLvl["ext"])&&is_int($aLvl["ext"]))$iExt=$aLvl["ext"];
            if(isset($aLvl["usr"])&&is_int($aLvl["usr"]))$iUsr=$aLvl["usr"];
            if(!isset($iWll))$iWll=1;
            foreach($aMap as $iK=>$aLn){
                foreach($aLn as $iJ=>$iV){
                    $oT=new TileCls(($iV==$iWll)?:false,false,["iX"=>$iJ,"iY"=>$iK]);
                    if(($iV==$iEnt)and($oT->set_ent()))$aEnt[]=$oT;
                    if(($iV==$iExt)and($oT->set_ext()))$aExt[]=$oT;
                    if(($iV==$iUsr)and(!$aPos))$aPos=$oT->get_pos();
                    $aLnn[]=$oT;
                    $iX=($iJ>$iX)?$iJ:$iX;//max horiz
                }
                $aMpn[]=$aLnn;unset($aLnn);
                $iY=($iK>$iY)?$iK:$iY;//max vert
            }
            $this->aMap=$aMpn;
            $this->aSz=["iX"=>$iX,"iY"=>$iY];
            $this->oJoy=new JoysCls();
            if(!$aEnt)$this->get_ent();
            if(!$aExt)$this->get_ext();
            if(!$this->set_usr(null,$aPos))throw new ExcCls("No set user",ExcCls::DEBUG);
            $bR=true;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
    }
    public static function get_map($mMp){
        try{
            if(!$mMp)throw new ExcCls("No map identificator",ExcCls::DEBUG);
            if(is_int($mMp))
                $sMpn=BDR.self::PTH."level$mMp.php";
            elseif(is_string($mMp))
                $sMpn=BDR.self::PTH.$mMp;
            if(!$sMpn)throw new ExcCls("No map file name",ExcCls::DEBUG);
            if(!is_readable($sMpn))throw new ExcCls("No read map file",ExcCls::DEBUG);
            include_once($sMpn);
            $aLvl["mmp"]=$mMp;
            $oMap=new self($aLvl);
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $oMap;}
    }
    public function get_map_id(){
        return $this->mMp;
    }
    public function get_map_nm(){
        return $this->sNm;
    }
    public function get_map_dsc(){
        return $this->sDsc;
    }
    public function show_map(){
        return MapPicCls4MapIfc::show_map($this->aMap);
    }
    public function show_joy(){
        return $this->oJoy->show_joy();
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
            foreach(end($this->aMap) as $iK=>$oT){//entry can be only in lower row by default
                if(!is_object($oT))throw new ExcCls("No tile",ExcCls::DEBUG);
                if($oT->set_ent())$aEnt[]=$oT;
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
            foreach(reset($this->aMap) as $iK=>$oT){//exit can be only in upper row by default
                if(!is_object($oT))throw new ExcCls("No tile",ExcCls::DEBUG);
                if($oT->set_ext())$aExt[]=$oT;
            }
            if(!$aExt)throw new ExcCls("No exits",ExcCls::DEBUG);
            $this->aExt=$aExt;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $this->aExt;}
    }
    public function is_win(){
        try{
            if(!$aPos=self::chk_pos(end($this->aHis)))throw new ExcCls("No user position",ExcCls::DEBUG);
            extract($aPos);
            $bR=$this->aMap[$iY][$iX]->is_ext();
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $bR;}
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
    public function set_usr($iDir=null,array $aPos=null){
        try{
            if(!$aMap=$this->aMap)throw new ExcCls("No map",ExcCls::DEBUG);
            if(!$oJoy=$this->oJoy)throw new ExcCls("No joystick",ExcCls::DEBUG);
            if(isset($iDir)){
                if(!$aPsw=self::chk_pos(end($this->aHis)))throw new ExcCls("No user position",ExcCls::DEBUG);
                extract($aPsw);
                if(!$aMap[$iY][$iX]->set_usr(false))throw new ExcCls("No unset user tile",ExcCls::DEBUG);
                $aPsn=$this->get_pos_nxt($iDir,$aPsw,true);
                if(!$aMap[$aPsn["iY"]][$aPsn["iX"]]->set_usr(true))throw new ExcCls("No set user tile",ExcCls::DEBUG);
                $this->aHis[]=$aPsn;
                $aDrs=$this->get_dir($aPsn);
                if(!$this->recover_track($aPsw,$aPsn))throw new ExcCls("No recover track",ExcCls::DEBUG);
            }else{
                if(!$aPos){
                    if(!$aEnt=$this->aEnt)throw new ExcCls("No entries",ExcCls::DEBUG);
                    if(!is_object($oT=reset($aEnt)))throw new ExcCls("No entry tile",ExcCls::DEBUG);
                    if(!$aPos=$oT->get_pos())throw new ExcCls("No entry postion",ExcCls::DEBUG);}
                if(!$aPos=self::chk_pos($aPos))throw new ExcCls("No match user postion",ExcCls::DEBUG);
                extract($aPos);
                if(!$aMap[$iY][$iX]->set_usr(true))throw new ExcCls("No set user tile",ExcCls::DEBUG);
                $this->aHis[]=$aPos;
                $aDrs=$this->get_dir($aPos);
                if(!$this->recover_area($aPos))throw new ExcCls("No recover area",ExcCls::DEBUG);
            }
            $oJoy->set_dir($aDrs);
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