<?php
namespace core\lib\cls;
class LngFileCls4LngIfc implements \core\lib\ifc\LngIfc
{
    const PTH="/core/lib/lng/";
    private $sLngDef=en;
    private $sLng;
    function __construct(){
        if(!$this->sLng)$this->sLng=$this->sLngDef;
    }
    public function t($sMsg){
        $oLng=new self;
        if(!file_exists($sFnm=BDR.self::PTH.$oLng->sLng))fopen($sFnm,"w");//try to create language file
        if(file_exists)
            if((strpos(file_get_contents($sFnm),$sMsg)===false)and($rF=fopen($sFnm,"ab"))and($bWrt=fwrite($rF,"$sMsg\n")))//if not found english message try to write it in
                fclose($rF);//and close
        unset($oLng);
        $sMsgr=$sMsg;
        return $sMsgr;
    }
}