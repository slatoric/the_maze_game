<?php
namespace core\lib\cls;
class LngFileCls4LngIfc implements \core\lib\ifc\LngIfc
{
    const PTH="/core/lib/lng/";
    const LNG_DEF="en";
    private $sLngDef=self::LNG_DEF;
    private $sLng;
    function __construct(){
        if(!$this->sLng)$this->sLng=$this->sLngDef;
    }
    public function t($sMsg){
        $oLng=new self;//to activate constructor
        if(!file_exists($sFnm=BDR.self::PTH.$oLng->sLng)){//try to create language file
            fopen($sFnm,"w");
            $bWrt=fwrite($rF,"{$oLng->sLng}\n");
        }
        if(file_exists($sFnm))
            if((strpos(file_get_contents($sFnm),$sMsg)===false)and($rF=fopen($sFnm,"ab"))and($bWrt=fwrite($rF,"$sMsg\n")))//if not found english message try to write it in
                fclose($rF);//and close
        unset($oLng);
        $sMsgr=$sMsg;
        return $sMsgr;
    }
    public static function tln($sLn){
        try{
            if(!$sLn)throw new ExcCls("No line",ExcCls::DEBUG);
            if(!$aLn=explode("<==>",trim($sLn)))throw new ExcCls("No explode line",ExcCls::DEBUG);
            if(count($aLn)>2)array_splice($aLn,2);
            foreach($aLn as $iK=>$sL)$aLn[$iK]=trim($sL);//clean up
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $aLn;}
        
    }
    public static function get_lnm($sLng){
        try{
            if(!$sLng)throw new ExcCls("No language code",ExcCls::DEBUG);
            if(!file_exists($sFnm=BDR.self::PTH.$sLng))throw new ExcCls("No language file",ExcCls::DEBUG);
            if(!$rF=fopen($sFnm,"r"))throw new ExcCls("No read language file",ExcCls::DEBUG);
            if(!$sLn=fgets($rF))throw new ExcCls("No read first line",ExcCls::DEBUG);
            if(!$aLn=self::tln($sLn))throw new ExcCls("No parse first line",ExcCls::DEBUG);
            $sLng_nm=($aLn[1])?:$sLng;
        }catch(ExcCls $eExc){
            $eExc->man();
            throw $eExc;
        }finally{
            return $sLng_nm;}
    }
    public static function get_lngs(){
        if($rD=opendir(BDR.self::PTH)){
            while(($sF=readdir($rD))!==false)
                if($sF!=".."&&$sF!=".")$aLng[$sF]=self::get_lnm($sF);
            closedir($rD);}
        if(!$aLng)$aLng[self::LNG_DEF]=self::get_lnm(self::LNG_DEF);
        return $aLng;
    }
}