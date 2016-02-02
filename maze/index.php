<?php
namespace lib;//namespace for all library
require_once("ald.php");//registers standart autoloader
$aMap=[
[1,0,1,1,1,1,1,1,1],
[1,0,0,0,0,0,0,0,1],
[1,1,1,1,1,1,1,0,1],
[1,0,0,0,0,0,0,0,1],
[1,0,1,1,0,1,1,1,1],
[1,0,0,1,0,0,0,0,1],
[1,1,1,1,1,1,1,0,1],
[1,0,0,0,0,0,0,0,1],
[1,1,1,1,0,1,1,1,1],
];
//echo "<pre>";var_dump($_REQUEST);echo "</pre>";
$oMap=new cls\MapCls($aMap);
//echo "<pre>";var_dump($oMap);echo "</pre>";
$oMap->toggle(false);
//echo "<pre>";var_dump($aMaph);echo "</pre>";
$oMap->get_entry();
$oMap->get_exit();
$oMap->set_user(null);
$oMap->set_user(cls\MapCls::UP);
$oMap->set_user(cls\MapCls::RT);
$oMap->set_user(cls\MapCls::UP);
$oMap->set_user(cls\MapCls::LT);
$oMap->set_user(cls\MapCls::UP);
$oMap->set_user(cls\MapCls::RT);
$oMap->set_user(cls\MapCls::UP);
$oMap->set_user(cls\MapCls::LT);
$oMap->set_user(cls\MapCls::UP);
$oMap->set_user(cls\MapCls::DN);
$oMap->draw();
//echo "<pre>oMap";var_dump($oMap);echo "</pre>";

$oJoy=new cls\JoysCls([0,2,4,6]);
//echo "<pre>oJoy";var_dump($oJoy);echo "</pre>";
$sHtm=$oJoy->draw_joy();
echo $sHtm;
$oJoy->get_dir();
//echo "<pre>oJoy";var_dump($oJoy);echo "</pre>";