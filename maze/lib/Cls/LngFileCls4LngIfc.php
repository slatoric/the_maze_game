<?php
namespace lib\cls;
class LngFileCls4LngIfc implements \lib\ifc\LngIfc
{
    private $sPth="/lib/lng/";
    private $sLngDef=en;
    private $sLng;
    function __construct(){
        if(!$this->sLng)$this->sLng=$this->sLngDef;
    }
    public function t($sMsg){
        $oLng=new self;
        if(!file_exists($sFnm=BDR.$oLng->sPth.$oLng->sLng))fopen($sFnm,"w");//try to create language file
        if(file_exists)
            if((strpos(file_get_contents($sFnm),$sMsg)===false)and($rF=fopen($sFnm,"ab"))and($bWrt=fwrite($rF,"$sMsg\n")))//if not found english message try to write it in
                fclose($rF);//and close
        unset($oLng);
        $sMsgr=$sMsg;
        return $sMsgr;
    }
}