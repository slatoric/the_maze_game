<?php
namespace lib\cls;
class MazeCls
{
    public function draw_map(array $aMap){
        return MapPicCls4MapIfc::draw_map($aMap);
    }
}