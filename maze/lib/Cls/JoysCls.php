<?php
namespace lib\cls;
class JoysCls
{
    const UP=0,RT=2,DN=4,LT=6;
    private $sDir;
    private $aDrs;
    function __construct(array $aDrs=null){
        $this->set_dir($aDrs);
    }
    public function get_dir(){
        if($_REQUEST["UP"])$this->sDir=self::UP;
        elseif($_REQUEST["DN"])$this->sDir=self::DN;
        elseif($_REQUEST["LT"])$this->sDir=self::LT;
        elseif($_REQUEST["RT"])$this->sDir=self::RT;
        return $this->sDir;
    }
    public function set_dir(array $aDrs=null){
        $this->aDrs=$aDrs;
    }
    public function draw_joy(){
        $sUpd=$sDnd=$sLtd=$sRtd=" disabled='disabled'";
        if($aDrs=$this->aDrs){
            if(in_array(0,$aDrs))$sUpd="";
            if(in_array(2,$aDrs))$sRtd="";
            if(in_array(4,$aDrs))$sDnd="";
            if(in_array(6,$aDrs))$sLtd="";}
        $sHtm="
            <form action='' method='post'>
                <table>
                    <tr><td></td><td><input type='submit' name='UP' value='/\'{$sUpd}></td><td></td></tr>
                    <tr><td><input type='submit' name='LT' value='<'{$sLtd}></td><td></td><td><input type='submit' name='RT' value='>'{$sRtd}></td></tr>
                    <tr><td></td><td><input type='submit' name='DN' value='\/'{$sDnd}></td><td></td></tr>
                </table>
            </form>";
        return $sHtm;
    }
}