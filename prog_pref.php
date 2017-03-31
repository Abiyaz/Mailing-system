<?php
if ($config == "true"){
    begin_page(0,"",$stylesheet);
    echo "<form action=\"$_SERVER[PHP_SELF]?handling=config\" method=\"post\">\n";
    echo "<table border=\"1\" align=\"center\" cellspacing=\"1\" cellpadding=\"2\" bordercolor=\"grey\">\n";
    echo "<tr>\n";
    echo "<td class=list>$s_language</td>\n";
    echo "<td class=list><select name=\"lang\">\n";

    echo "<option value=\"$languageFile\">$languageFile\n";
    if($dir=opendir('language')){
        while($file=readdir($dir)){
            if('.'==$file || '..'==$file)
                continue;
            if(is_dir('language/'.$file))
                continue;
            if (substr($file,-3)=='php'){
                $l = strpos($file, ".");
                $taal = substr($file,0,$l);
                if($taal != $languageFile){
                    echo "<option value=\"$taal\"";
                    echo ">$taal\n";
                }
            }
        }
	closedir($dir);
    }
    echo "</select>\n";
    echo "</td>\n";
    echo "<td class=list>&nbsp;&nbsp;$s_expllang</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=list>$s_style</td>\n";
    echo "<td class=list><select name=\"choicestyle\">\n";

    echo "<option value=\"$stylesheet\">$stylesheet\n";
    if($dir=opendir('style')){
        while($file=readdir($dir)){
            if('.'==$file || '..'==$file)
                continue;
            if(is_dir('style/'.$file))
                continue;
            $l = strpos($file, ".");
            $stijl = substr($file,0,$l);
            if ($stijl != $stylesheet){
                echo "<option value=\"$stijl\" ";
                echo ">$stijl\n";
            }
        }
	closedir($dir);
    }
    echo "</select>";
    echo "</td>\n";
    echo "<td class=list>&nbsp;&nbsp;$s_explstyle</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=list>$s_logout</td>\n";
    echo "<td class=list><input type=text name=autologout value=\"$logout\" size=10> $s_seconds</td>\n";
    echo "<td class=list>&nbsp;&nbsp;$s_expllogout</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=list>$s_list_nr</td>\n";
    echo "<td class=list><input type=text name=ListMsg value=\"$ListMessages\" size=10> $s_list_msg</td>\n";
    echo "<td class=list>&nbsp;&nbsp;$s_explmsglist</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=list>$s_sub_length</td>\n";
    echo "<td class=list><input type=text name=sub_len value=\"$max_sub_len\" size=10></td>\n";
    echo "<td class=list>&nbsp;&nbsp;$s_explsub_length</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=list>$s_smtp_server</td>\n";
    if(!$smtp_server){ $smtp_server = $GLOBALS["server"]; }
    echo "<td class=list><input type=text name=smtp value=\"$smtp_server\" size=25></td>\n";
    echo "<td class=list>&nbsp;&nbsp;$s_explsmtp_server</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=list>$s_signature</td>\n";
    echo "<td class=list><textarea rows=\"3\" name=\"sig\" cols=\"25\">$signature</textarea></td>\n";
    echo "<td class=list>&nbsp;&nbsp;$s_explsignature</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=list>$s_folder</td>\n";
    if($vmbox_disable == "1") {
        echo "<td class=list>$s_not_allowed</td>\n";
    }
    else {
        echo "<td class=list><textarea rows=\"3\" name=\"pref_folder\" cols=\"25\">$folder</textarea></td>\n";
    }
    echo "<td class=list>&nbsp;&nbsp;$s_explfolder</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=list>$s_autobcc</td>\n";
    echo "<td class=list><input type=\"text\" name=\"auto_bcc\" value=\"$autobcc\" size=\"25\"></td>\n";
    echo "<td class=list>&nbsp;&nbsp;$s_explautobcc</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "<center><input type=\"submit\" value=\"$s_save\" class=\"button\"></center>\n";
    echo "</form>\n";
    end_page();
    exit;
}

if ($handling == "config"){
    $fp3 = fopen("userdata/$ind_config.config", "w");
    flock($fp3,2);
    fwrite($fp3,"<?php\n$");
    fwrite($fp3,"languageFile = \"");
    fwrite($fp3,"$lang\";\n$");
    fwrite($fp3,"stylesheet = \"");
    fwrite($fp3,"$choicestyle\";\n$");
    fwrite($fp3,"logout = \"");
    fwrite($fp3,"$autologout\";\n$");
    fwrite($fp3,"ListMessages = \"");
    fwrite($fp3,"$ListMsg\";\n$");
    fwrite($fp3,"max_sub_len = \"");
    fwrite($fp3,"$sub_len\";\n$");
    fwrite($fp3,"smtp_server = \"");
    fwrite($fp3,"$smtp\";\n$");
    fwrite($fp3,"signature = \"");
    fwrite($fp3,"$sig\";\n$");
    fwrite($fp3,"autobcc = \"");
    fwrite($fp3,"$auto_bcc\";\n?>");
    flock($fp3,3);
    fclose($fp3);

    if (!empty($save_mailaddress)) {
        $temp1 = explode("@",$save_mailaddress);
        $user_chk = $temp1[0];
        $server_chk = $temp1[1];
        $ind_config_chk = $server_chk."_".$user_chk;
        $fp3 = fopen("userdata/$ind_config_chk.config", "w");
        flock($fp3,2);
        fwrite($fp3,"<?php\n$");
        fwrite($fp3,"languageFile = \"");
        fwrite($fp3,"$lang\";\n$");
        fwrite($fp3,"stylesheet = \"");
        fwrite($fp3,"$choicestyle\";\n$");
        fwrite($fp3,"logout = \"");
        fwrite($fp3,"$autologout\";\n$");
        fwrite($fp3,"ListMessages = \"");
        fwrite($fp3,"$ListMsg\";\n$");
        fwrite($fp3,"max_sub_len = \"");
        fwrite($fp3,"$sub_len\";\n$");
        fwrite($fp3,"smtp_server = \"");
        fwrite($fp3,"$smtp\";\n$");
        fwrite($fp3,"signature = \"");
        fwrite($fp3,"$sig\";\n$");
        fwrite($fp3,"autobcc = \"");
        fwrite($fp3,"$auto_bcc\";\n?>");
        flock($fp3,3);
        fclose($fp3);
    }

    $fp3 = fopen("userdata/$ind_config.vmbox", "w");
    flock($fp3,2);
    fwrite($fp3,"<?php\n$");
    fwrite($fp3,"auth_vmbox_user = \"");
    fwrite($fp3,"$user\";\n$");
    $split_temp = split("\n",$pref_folder);
    $lines_ok = array();
    foreach($split_temp as $line){
        if(!ereg(" ", $line)){
            array_push ($lines_ok, $line);
        }
    }
    $pref_folder = join("\n", $lines_ok);
    fwrite($fp3,"folder = \"");
    fwrite($fp3,"$pref_folder\";\n?>");
    flock($fp3,3);
    fclose($fp3);

    for ($i = 0; $i < count($lines_ok); $i++) {
        if (!file_exists("userdata/".$ind_config."_".trim($lines_ok[$i]).".vmbox"))
            copy($addressbook, "userdata/".$ind_config."_".trim($lines_ok[$i]).".vmbox");
    }

    begin_page(0,"",$stylesheet);
    echo "<form action=\"$_SERVER[PHP_SELF]?config=true\" method=\"post\">\n";
    echo "$s_settingsSaved";
    echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
    echo "</form>\n";
    end_page();
    exit;
}
?>