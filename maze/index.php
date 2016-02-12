<?php
namespace core\lib;//namespace for all library
require_once("ald.php");//registers standart autoloader
if(!defined("BDR"))define("BDR",getcwd());
if(!defined("IN_DEV"))define("IN_DEV",true);
if(!session_id())session_start();
//echo session_cache_expire();
if(!function_exists(__NAMESPACE__."\\exc_hnd")){
    function exc_hnd($e){
        echo $e->getMessage(), " cd: {$e->getCode()}", '<pre>', $e->getTraceAsString(), '</pre>';
    }
}
set_exception_handler(__NAMESPACE__."\\exc_hnd");
if(IN_DEV){
    echo "<pre>_REQUEST";var_dump($_REQUEST);echo "</pre>";
    echo "<pre>_SESSION";var_dump($_SESSION);echo "</pre>";
    echo "<pre>session_id";var_dump(session_id());echo "</pre>";
}
$oTmg=new cls\MazeCls();
$oTmg->run();