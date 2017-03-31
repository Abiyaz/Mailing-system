<?php
if (!$server || !$port || !$user || !$accesscode) {
    begin_page(0,"",$stylesheet);
    echo "<br><br>\n<center>\n";
    echo "<form action=\"$_SERVER[PHP_SELF]\" method=\"post\" name=\"login\">\n";
	echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"350\">\n";
    echo "<tr><td class=\"tableheader\" colspan=\"2\"><b>$s_login_page<b></td></tr>\n";
	print( '<a href="password.php"><font color="black"><h2><b>Signup</b></h2></font></a>' );
    if ($fixedSender) {
        echo "<td class=\"list\">".htmlspecialchars($fixedSender)."</td></tr>\n";
    }
    if($hideServer == 1)
    {
        echo "<input type=\"hidden\" name=\"server\" value=\"$fixedServer\">\n";
        echo "<input type=\"hidden\" name=\"port\"   value=\"$fixedServerPort\">\n";
    }
    else
    {
        echo "<tr><td class=\"list\">$s_server</td>\n";
        if ($changeableServer == "yes"){
            echo "<td class=\"list\"><input type=\"text\" name=\"server\" size=\"20\" value=\"$fixedServer\"></td></tr>\n";
        }else{
            echo "<td class=\"list\">$fixedServer</td>\n";
            echo "<input type=\"hidden\" name=\"server\" size=\"20\" value=\"$fixedServer\"></td></tr>\n";
        }
        echo "<tr><td class=\"list\">$s_port</td>\n";
        if ($fixedServer) {
            if ($fixedServerPort) {
                echo "<td class=\"list\"><input type=\"hidden\" name=\"port\" value=\"$fixedServerPort\">\n";
                echo "$fixedServerPort</td></tr>\n";
            }
            else {
                echo "<td class=\"list\"><input type=\"hidden\" name=\"port\" value=\"110\">\n";
                echo "110</td></tr>\n";
            }
        }
        else {
            echo "<td class=\"list\"><input type=\"text\" name=\"port\" size=\"20\" value=\"110\"></td></tr>\n";
        }
    }
    echo "<tr><td class=\"list\">$s_user</td>\n";
    if ($fixedUser) {
        echo "<input type=\"hidden\" name=\"user\" value=\"$fixedUser\">\n";
        echo "<td class=\"list\">$fixedUser</td></tr>\n";
    }
    else {
        echo "<td class=\"list\"><input type=\"text\" name=\"user\" size=\"20\"></td></tr>\n";
    }
    echo "<tr><td class=\"list\">$s_password</td>\n";
    echo "<td class=\"list\"><input type=\"password\" name=\"accesscode\" size=\"20\"></td></tr>\n";
    echo "<tr><td class=\"list\">&nbsp;</td>\n";
    echo "<td class=\"list\"><input type=\"submit\" class=\"button\" value=\"$s_signin\"></td></tr>\n";
    echo "<tr><td class=\"tableheader\" colspan=\"2\">$s_cookies</td></tr>\n";
    echo "</table>\n";
	echo "</div>\n";
    echo "</form>\n\n";
    echo "</center>\n";
    echo "<script type=\"text/javascript\">\n";
    echo "<!--\n";
    if ($fixedUser == "")
    {
        echo "document.login.user.focus();\n";
    }
    else
        echo "document.login.accesscode.focus();\n";
    echo "// -->\n";
    echo "</script>\n";
    if ($vers){                            
       if(file_exists("prog_vers.php")){   
          include("prog_vers.php");       
       }                                   
    }                                      
    end_page();
    exit;
}
do {
   $fp = fsockopen($server, $port) or error($s_cannotConnect, 1, 1, $stylesheet);
   $foersteLinje = fgets($fp, 1024);
   fputs($fp, "USER $user\r\n");
   $andenLinje = fgets($fp, 1024);
   fputs($fp, "PASS $accesscode\r\n");
   $pop_answer=fgets($fp, 1024);
   if (preg_match("/\[AUTH\]/", $pop_answer)){
      fputs($fp, "QUIT\r\n");
      fclose($fp);
      error($s_wrongPassword, 1, 1, $stylesheet);
   }
   else if (preg_match("/\[IN-USE\]/", $pop_answer)){
      fputs($fp, "QUIT\r\n");
      fclose($fp);
      $end_time = time() + 1;
      while(time() <= $end_time){
      }
   }
   else if(preg_match("/-ERR/", $pop_answer)){
      fputs($fp, "QUIT\r\n");
      fclose($fp);
      error($s_pop3_error . "<br>\r\n$server:$port \"$pop_answer\"", 1, 1, $stylesheet);
   }
} while (preg_match("/\[IN-USE\]/", $pop_answer));


if ($state == ""){
    if(ini_get('register_globals')) {
        session_register("mailaddress");
        session_register("server");
        session_register("port");
        session_register("user");
        session_register("accesscode");
    }
    else {
        $_SESSION["mailaddress"] = $mailaddress;
        $_SESSION["server"] = $server;
        $_SESSION["port"] = $port;
        $_SESSION["user"] = $user;
        $_SESSION["accesscode"] = $accesscode;
    }
    setcookie("save_mailaddress", $mailaddress, time()+2592000);

    $ind_config = $server."_".$user;
    $config_file = "$ind_config.config";
    $addressb = "$ind_config.addr";
    $groupb = "$ind_config.group";

    if (!file_exists("userdata/$config_file")) {
        copy($addressbook,"userdata/$config_file");
        $fp3 = fopen("userdata/$config_file", "w");
        flock($fp3,2);
        fwrite($fp3,"<?php \n\n\$languageFile = \"$languageFile\"; \n\$stylesheet = \"$stylesheet\"; \n\$logout = \"$logout\" \n\n?>");
        flock($fp3,3);
        fclose($fp3);
    }
    else include("userdata/$config_file");

    if (!file_exists("userdata/$addressb")) {
        copy($addressbook,"userdata/$addressb");
    }
    if (!file_exists("userdata/$groupb")) {
        copy($addressbook, "userdata/$groupb");
    }
    if (file_exists("userdata/$ind_config.vmbox")) {
        include("userdata/$ind_config.vmbox");
        if ($user != $auth_vmbox_user){
            $vmbox_disable = "1";
        }
    }
}
?>