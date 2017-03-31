<?php

if(isset($GLOBALS['Version_Get'])){ return; }
function myErrorHandler_std($errno, $errstr, $errfile, $errline){
}
error_reporting('FATAL' | 'ERROR' | 'WARNING');

register_globals_session();

function register_globals_session(){
    $reg_global_steps = "";
    if(!ini_get('register_globals')){
        if(isset($GLOBALS["HTTP_SERVER_VARS"])){
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: HTTP_SERVER_VARS (".count($GLOBALS["HTTP_SERVER_VARS"])."<br>\n"; }
            foreach($GLOBALS["HTTP_SERVER_VARS"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["HTTP_SERVER_VARS"][$key];
            }
        }
        else{
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: _SERVER (".count($GLOBALS["_SERVER"]).")<br>\n"; }
            foreach($GLOBALS["_SERVER"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["_SERVER"][$key];
            }
        }
		if(isset($GLOBALS["HTTP_COOKIE_VARS"])){
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: HTTP_COOKIE_VARS (".count($GLOBALS["HTTP_COOKIE_VARS"]).")<br>\n"; }
            foreach($GLOBALS["HTTP_COOKIE_VARS"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["HTTP_COOKIE_VARS"][$key];
            }
        }
        else{
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: _COOKIE (".count($GLOBALS["_COOKIE"]).")<br>\n"; }
            foreach($GLOBALS["_COOKIE"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["_COOKIE"][$key];
            }
        }
        if(isset($GLOBALS["HTTP_POST_VARS"])){
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: HTTP_POST_VARS (".count($GLOBALS["HTTP_POST_VARS"]).")<br>\n"; }
            foreach($GLOBALS["HTTP_POST_VARS"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["HTTP_POST_VARS"][$key];
            }
        }
        else{
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: _POST (".count($GLOBALS["_POST"]).")<br>\n"; }
            foreach($GLOBALS["_POST"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["_POST"][$key];
            }
        }
        if(isset($GLOBALS["HTTP_GET_VARS"])){
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: HTTP_GET_VARS (".count($GLOBALS["HTTP_GET_VARS"]).")<br>\n"; }
            foreach($GLOBALS["HTTP_GET_VARS"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["HTTP_GET_VARS"][$key];
            }
        }
        else{
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: _GET (".count($GLOBALS["_GET"]).")<br>\n"; }
            foreach($GLOBALS["_GET"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["_GET"][$key];
            }
        }
        if(isset($GLOBALS["HTTP_POST_FILES"])){
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: HTTP_POST_FILES (".count($GLOBALS["HTTP_POST_FILES"]).")<br>\n"; }
            foreach($GLOBALS["HTTP_POST_FILES"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["HTTP_POST_FILES"][$key];
            }
        }
        else{
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register: _FILES (".count($GLOBALS["_FILES"]).")<br>\n"; }
            foreach($GLOBALS["_FILES"] as $key => $value){
                $GLOBALS[$key] = $GLOBALS["_FILES"][$key];
            }
        }

        session_start();
        if($GLOBALS["HTTP_SESSION_VARS"] || $GLOBALS["_SESSION"]){
            session_set_cookie_params(0, $GLOBALS["SCRIPT_NAME"], $GLOBALS["SERVER_NAME"]);
            if(isset($GLOBALS["HTTP_SESSION_VARS"])){
                if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register Session: HTTP_SESSION_VARS (".count($GLOBALS["HTTP_SESSION_VARS"]).")<br>\n"; }
                foreach($GLOBALS["HTTP_SESSION_VARS"] as $key => $value){
                    $GLOBALS[$key] = $GLOBALS["HTTP_SESSION_VARS"][$key];
                }
            }
            else{
                if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register Session: _SESSION (".count($GLOBALS["_SESSION"]).")<br>\n"; }
                foreach($GLOBALS["_SESSION"] as $key => $value){
                    $GLOBALS[$key] = $GLOBALS["_SESSION"][$key];
                }
            }
        }
        if(isset($reg_glob_dbg)){ echo $reg_global_steps; }
        if(isset($reg_glob_dbg)){ print_var($GLOBALS); }

    }
    else {
        if($GLOBALS["HTTP_COOKIE_VARS"]["PHPSESSID"]){
            if(isset($reg_glob_dbg)){ $reg_global_steps .= "Register Session: register_global=on<br>\n"; }
            session_set_cookie_params(0, $SCRIPT_NAME, $SERVER_NAME);
            session_start();
        }
        if(isset($reg_glob_dbg)){ echo $reg_global_steps; }
    }
}
?>
