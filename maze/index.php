<?php
namespace core\lib;//namespace for all library
require_once("ald.php");//registers standart autoloader
if(!defined("BDR"))define("BDR",getcwd());
if(!session_id())session_start();
//echo session_cache_expire();
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
echo "<pre>_REQUEST";var_dump($_REQUEST);echo "</pre>";
echo "<pre>_SESSION";var_dump($_SESSION);echo "</pre>";
$oTmg=new cls\MazeCls();
echo "<pre>oTmg";var_dump($oTmg);echo "</pre>";

echo $oTmg->set_user();
if($oUsr=$oTmg->get_user()){
    echo "<pre>oUsr";var_dump($oUsr);echo "</pre>";
    }
