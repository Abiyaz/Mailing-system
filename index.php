<?php
include("config-mail.php");
include("prog_funktions.php");
include("std_light.php");
if($flow_contr == "1"){
    if (!$flow_error_debug = fopen("userdata/Flow_Debug_Error.log", "a")) {
        echo "Error logfile open.";
        exit;
    }
    $flow_time = time();
    $flow_date = date("d.m.Y H:i:s", $flow_time);
    $flow_text = "\n$flow_date\n000 >>> index.php -> START\n";
    $flow_text .= "000 >>> index.php -> PHP Version: " . phpversion() . "\n";
    $flow_text .= "000 >>> index.php -> Program Start -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
    fwrite($flow_error_debug, $flow_text);
}
if ($fixedServer || $save_mailaddress) {
    if (!empty($user) && !empty($server)) {
        $ind_config = strtolower($server."_".$user);
        $user_config_file = "$ind_config.config";
        if (file_exists("userdata/$user_config_file")) {
            include("userdata/$user_config_file");
            require("language/$languageFile.php");
        }
    }
    else {
        $temp1 = explode("@",strtolower($save_mailaddress));
        $user_chk = $temp1[0];
        $server_chk = $temp1[1];
        $ind_config_chk = $server_chk."_".$user_chk;
        if (file_exists("userdata/$ind_config_chk.config")) {
            include("userdata/$ind_config_chk.config");
            require("language/$languageFile.php");
        }
        else {
            require("language/$languageFile.php");
        }
    }
}
else {
    require("language/$languageFile.php");
}
if ($logUd == 1) {
    setcookie("save_mailaddress", $mailaddress, time()+2592000);
    session_unset();
    session_destroy();
    begin_page(0,"",$stylesheet);
    echo "<br><br><br><center><h3>$s_loggedOut</h3>\n";
    echo "$s_reenter\n<br><br></center>";
    end_page();
    exit;
}
if ($frame == "left") {
    begin_page(0, "", $stylesheet,1,"left");
    echo "<CENTER>\n<FORM name=\"clock\" action=\"\">\n";
    echo "<input type=\"text\" name=\"date\" size=\"7\" value=\"\" class=\"left\">\n";
    echo "<input type=\"text\" name=\"time\" size=\"7\" value=\"\" class=\"left\">\n";   
    echo "</FORM>\n";
	echo "<div style=\"text-align:center;\">";
	echo "<div id=\"menu\">";
	echo "<ul>";
    echo "<li><a href=\"$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=1\" target=bottom>Inbox</a></li>\n";
    echo "<li><a href=\"$_SERVER[PHP_SELF]?handling=write\" target=bottom>Compose</a></li>\n";
    echo "<li><a href=\"$_SERVER[PHP_SELF]?address=true\" target=bottom>Contacts</a></li>\n";
    echo "<li><a href=\"Help.html\" target=bottom>Help</a>\n";
    echo "<li><a href=\"$_SERVER[PHP_SELF]?config=true\" target=bottom>Setttings</a></li>\n";
    echo "<li><a href=\"$_SERVER[PHP_SELF]?logUd=1\" target=_top>Logout</a></li>\n";
    echo "</ul>";
	echo "</div>";
	echo "<FORM name=\"countdown\" action=\"\">\n";
	echo "<Font color=\"black\"> \n Session Ends: \n </Font> \n";
    echo "<input type=\"text\" name=\"time\" size=\"7\" value=\"\" class=\"left\">\n";
    echo "</div>";
	echo "</FORM>\n</CENTER>\n";
    
    end_page(1,$logout);
    exit;
}
if ($frame == "bottom") {
    begin_page(0, "",$stylesheet,1,"bottom");
    echo "<br><br><br><br>\n";
    echo "<center>\n";
    echo "<span style=\"background: Purple; color: white; font-size: 14pt; font-style: italic; font-weight: bold\">";
    echo "&nbsp; Intranet Mail &nbsp;";
    echo "</span>\n";
    echo "</center>\n";
    end_page();
    exit;
}
if($flow_contr == "1"){
    $time_now = time();
    $time_stamp = $time_now - $flow_time;
    $time_stamp = substr("000$time_stamp", -3);
    $flow_text = "$time_stamp >>> index.php -> prog_login.php -> START\n";
    fwrite($flow_error_debug, $flow_text);
}
include("prog_login.php");
if($flow_contr == "1"){
    $time_now = time();
    $time_stamp = $time_now - $flow_time;
    $time_stamp = substr("000$time_stamp", -3);
    $flow_text = "$time_stamp >>> index.php -> prog_login.php -> STOP\n";
    fwrite($flow_error_debug, $flow_text);
}
if ($handling == "send") {
    $ind_config = $server."_".$user;
    include("prog_mail_write.php");
    exit;
}
    if (!$QUERY_STRING) {
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
        echo "<html>\n";
        echo "<head>\n";
        echo "<META http-equiv=Content-Type content=\"text/html; charset=iso-8859-1\">\n";
        echo "<title>Intranet Mail</title>\n";
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style/".$stylesheet.".css\">\n";
        if ($logout != null || $logout != 0)
            echo "<meta http-equiv=\"Refresh\" content=\"$logout; url=?logUd=1\">\n";
        echo "</head>\n";        
        echo "<frameset rows=20%,* border=1 bordercolor=\"orange\">\n";
		echo "<frame src=\"$_SERVER[PHP_SELF]?frame=left\" name=left scrolling=no noresize>\n";
        echo "<frame src=\"$_SERVER[PHP_SELF]?frame=bottom\" name=bottom>\n";
        echo "</frameset>\n";
        echo "</frameset></html>";
    }
if ($handling == "write") {
    include("prog_mail_write.php");
}
if ($address == "true") {
    include("prog_addr.php");
}
if ($handling == "address") {
    include("prog_addr.php");
}
if ($address == "edit") {
    include("prog_addr.php");
}
if ($config == "true") {
    include("prog_pref.php");
}
if ($handling == "config") {
    include("prog_pref.php");
}
if ($frame == "inbox") {
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> START\n";
        fwrite($flow_error_debug, $flow_text);
    }
    include("prog_mail_list.php");
}
if ($frame == "folder_info") {
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $debug_details = $details;
        if(!$details){ $debug_details = "all"; }
        $flow_text = "$time_stamp >>> index.php -> prog_mail_file.php -> folder info for $debug_details -> START\n";
        fwrite($flow_error_debug, $flow_text);
    }
    $type = $frame;
    $mode = $details;
    include("prog_mail_file.php");
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $debug_details = "$details ($size)";
        if(!$details){ $debug_details = "all"; }
        $flow_text = "$time_stamp >>> index.php -> prog_mail_file.php -> folder info for $debug_details -> STOP\n";
        fwrite($flow_error_debug, $flow_text);
    }
    exit;
}
if ($showSource && $mailNumber) {
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_display.php -> START\n";
        fwrite($flow_error_debug, $flow_text);
    }
    include("prog_mail_display.php");
}
if ($showNormalHeaders) {
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_display.php -> START\n";
        fwrite($flow_error_debug, $flow_text);
    }
    include("prog_mail_display.php");
}
if ($function) {
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> SPEZIAL FUNCTION -> $function.php -> START\n";
        fwrite($flow_error_debug, $flow_text);
    }
    include("$function.php");
}
if($flow_contr == "1"){
    $time_now = time();
    $flow_date = date("d.m.Y H:i:s", $time_now);
    $time_stamp = $time_now - $flow_time;
    $time_stamp = substr("000$time_stamp", -3);
    $flow_text = "$time_stamp >>> index.php -> MemoryUsage: ". memory_get_usage() ." Bytes\n";
    $flow_text .= "$time_stamp >>> index.php -> MemoryUsage Peak: ". memory_get_peak_usage() ." Bytes\n";
    $flow_text .= "$time_stamp >>> index.php -> STOP\n$flow_date\n";
    fwrite($flow_error_debug, $flow_text);
}
?>