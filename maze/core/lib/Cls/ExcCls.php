<?php
namespace core\lib\cls;
class ExcCls extends \Exception
{
    const DEBUG=0, INFO=1, NOTICE=2, WARNING=3, ERROR=4, CRITICAL=5, ALERT=6, EMERGENCY=7;
    public function man(){
        //error_log($this->getMessage(),3,"err.log");
        //error_log(date('Y-m-d H:i:s').' '.serialize($this)."\n",3,'err.log');
        if($this->getCode()>self::WARNING)error_log(date('Y-m-d H:i:s')." {$this->getMessage()} {$this->getCode()} {$this->getFile()} {$this->getTraceAsString()}\n",3,"err.log");
        if(IN_DEV)echo $this->getMessage()," cd: {$this->getCode()}","<pre>",$this->getTraceAsString(),"</pre>";
    }
}