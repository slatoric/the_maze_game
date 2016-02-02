<?php
namespace lib\cls;
class UserCls
{
    private $aPos;
    private $aHis;//history
    function __construct(array $aPos){
        $this->set_pos($aPos);
    }
    public function get_pos(){
        return $this->aPos;
    }
    public function set_pos(array $aPos){
        $this->aPos=$aPos;
        $this->aHis[]=$aPos;
    }
}