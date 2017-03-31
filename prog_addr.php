<?php
if ($address == "true") {
    begin_page(0,"",$stylesheet);
    echo "<form action=\"$_SERVER[PHP_SELF]?handling=address\" method=\"post\">\n";
    echo "<input type=\"hidden\" name=\"email\" value=\"$s_add\">\n";
    echo "<table border=\"0\" cellspacing=1 cellpadding=2>\n";
    echo "<tr>\n";
    echo "<td class=\"list\">$s_name:</td>";
    echo "<td class=\"list\"><input type=\"text\" name=\"name\" size=\"36\" value=\"$book_name\"></td>";
    echo "<td width=\"50\">&nbsp;</td>\n";
    echo "<td class=\"list\">$s_group:</td>\n";
    echo "<td class=\"list\"><input type=\"text\" name=\"group\" size=\"36\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"list\">$s_email:</td>";
    echo "<td class=\"list\"><input type=\"text\" name=\"addr\" size=\"36\" value=\"$book_addr\"></td>\n";
    echo "<td width=\"50\">&nbsp;</td>\n";
    echo "<td class=\"list\" colspan=\"2\">\n";
    echo "<center><input type=\"submit\" value=\"$s_add\" class=\"button\">&nbsp;&nbsp;<input type=\"reset\" value=\"$s_clear\" class=\"button\"></center>\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"list\">$s_group</td>\n";
    echo "<td class=\"list\"><select name=\"add_to_group\">\n";
    echo "<option value=\"0\">\n";
    $contents1 = NULL;
    if (filesize("userdata/$groupb") != NULL || filesize("userdata/$groupb") != 0) {
      $fd1 = fopen("userdata/$groupb", "r");
      $contents1 = fread($fd1, filesize("userdata/$groupb"));
      fclose($fd1);
        $entries3 = explode(";",$contents1);
        for($i = 0; $i < count($entries3); $i++)
        {
            if ($entries3[$i] != ""){
                $entries4 = explode(",",$entries3[$i]);
                echo "<option value=\"".$entries4[0]."\">".decrypt($encrypter,$entries4[1])."\n";
            }
        }
    }
    echo "</select></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"list\" colspan=\"2\">\n";
    echo "<center><input type=\"submit\" value=\"$s_add\" class=\"button\">&nbsp;&nbsp;<input type=\"reset\" value=\"$s_clear\" class=\"button\"></center>\n";
    echo "</td>\n";
    echo "<td width=\"50\">&nbsp;</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";
    echo "<br><br>\n";
    $contents = NULL;
    if (filesize("userdata/$addressb") != NULL || filesize("userdata/$addressb") != 0) {
        $fd = fopen("userdata/$addressb", "r");
        $contents = fread($fd, filesize("userdata/$addressb"));
        fclose ($fd);
    }
    if ($contents == NULL)
        echo "$s_noAddresses\n";
    else{
        echo "<form action=\"$_SERVER[PHP_SELF]?handling=address\" method=\"post\">\n";
        echo "<input type=\"hidden\" name=\"email\" value=\"$s_remove\">\n";
        echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bordercolor=\"black\">\n";
        echo "<tr><td class=\"tableheader\">$s_delete</td>\n";
        echo "<td class=\"tableheader\">$s_name</td>\n";
        echo "<td class=\"tableheader\">$s_email</td>\n";
        echo "<td class=\"tableheader\">$s_group</td>\n";
        echo "<td class=\"tableheader\">$s_edit</td>\n";
        echo "</tr>\n";
        $entries2 = explode(";",$contents);
        for($i = 0; $i < count($entries2); $i++)
        {
            if ($entries2[$i] != ""){
                $entries = explode(",",$entries2[$i]);
                echo "<tr>\n";
                echo "<td class=\"list\"><input type=\"checkbox\" name=\"delete[]\" value=\"".decrypt($encrypter,$entries[1])."\"></td>\n";
                echo "<td class=\"list\">".decrypt($encrypter,$entries[0])."</td>\n";
                echo "<td class=\"list\">".decrypt($encrypter,$entries[1])."</td>";
                echo "<td class=\"list\">";
                for ($j = 0; $j < count($entries3); $j++)
                {
                    if ($entries3[$j] != "") {
                        $entries4 = explode(",",$entries3[$j]);
                        if ($entries4[0] == $entries[2]) {
                            echo decrypt($encrypter,$entries4[1]);
                        }
                    }
                }
                echo "&nbsp;</td>\n";
                echo "<td class=\"list\"><a href=\"$_SERVER[PHP_SELF]?address=edit&addr_email=".decrypt($encrypter,$entries[1])."\">$s_edit</a></td>\n";
                echo "</tr>\n";
            }
        }
        echo "</table><br>\n";
        echo "<input type=\"submit\" class=\"button\" value=\"$s_remove\">\n";
        echo "<input type=\"submit\" class=\"button\" name=\"sort\" value=\"$s_sort\">\n";
        echo "</form><br />\n";
    }

    if ($contents1 == NULL)
        echo "$s_noGroups\n";
    else {
        echo "<form action=\"$_SERVER[PHP_SELF]?handling=address\" method=\"post\">\n";
        echo "<input type=\"hidden\" name=\"group\" value=\"$s_remove\">\n";
        echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bordercolor=\"black\">\n";
        echo "<tr><td class=\"tableheader\">$s_delete</td>\n";
        echo "<td class=\"tableheader\">$s_group</td>\n";
        echo "<td class=\"tableheader\">$s_edit</td>\n";
        echo "</tr>\n";
        for($i = 0; $i < count($entries3); $i++)
        {
            if ($entries3[$i] != ""){
                $entries4 = explode(",",$entries3[$i]);
                echo "<tr>\n";
                echo "<td class=\"list\"><input type=\"checkbox\" name=\"deletegroup[]\" value=\"".$entries4[0]."\"></td>\n";
                echo "<td class=\"list\">".decrypt($encrypter,$entries4[1])."</td>\n";
                echo "<td class=\"list\"><a href=\"$_SERVER[PHP_SELF]?address=edit&group_name=".decrypt($encrypter,$entries4[1])."\">$s_edit</a></td>\n";
                echo "</tr>\n";
            }
        }
        echo "</table><br>\n";
        echo "<input type=\"submit\" class=\"button\" value=\"$s_remove\">\n";
        echo "</form>\n";
    }
    end_page();
}
if ($handling == "address") {
   if ($email == $s_add) {
        if ($addr != NULL && $group == NULL) {
            if (filesize("userdata/$addressb") != NULL || filesize("userdata/$addressb") != 0){
                $fd = fopen("userdata/$addressb", "r");
                $contents = fread($fd, filesize("userdata/$addressb"));
                fclose ($fd);
                $entries = explode(";",$contents);
            }
            $exists = "no";
            for($j = 0; $j < count($entries); $j++) {
                $entries2 = explode(",",$entries[$j]);
                if (chop(decrypt($encrypter,$entries2[1])) == $addr)
                    $exists = "yes";
            }
            if ($exists == "yes") {
                begin_page(0,"",$stylesheet);
                echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
                echo "$s_addr_exists\n";
                echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
                echo "</form>\n";
                end_page();
                exit;
            }
            else {
                $fp2 = fopen("userdata/$addressb", "a+");
                flock($fp2,2);
                fwrite($fp2,"".encrypt($encrypter,$name).",".encrypt($encrypter,strtolower($addr)).",".$add_to_group.";");
                flock($fp2,3);
                fclose($fp2);
                begin_page(0,"",$stylesheet);
                echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
                echo $s_email . " \"<b>" . $addr . "</b>\" " . $s_added;
                echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
                echo "</form>\n";
                end_page();
                exit;
            }
        }
        if ($addr == NULL && $group != NULL) {
            if (filesize("userdata/$groupb") != NULL || filesize("userdata/$groupb") != 0){
                $fd = fopen("userdata/$groupb", "r");
                $contents = fread($fd, filesize ("userdata/$groupb"));
                fclose ($fd);
                $entries = explode(";",$contents);
                $counter = count($entries);
            }
            $exists = "no";
            for($j = 0; $j < count($entries); $j++) {
                $entries2 = explode(",",$entries[$j]);
                if (chop(decrypt($encrypter,$entries2[1])) == $group)
                    $exists = "yes";
            }
            if ($exists == "yes") {
                begin_page(0,"",$stylesheet);
                echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
                echo "$s_group_exists\n";
                echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
                echo "</form>\n";
                end_page();
                exit;
            }
            else {
                $last_entry = explode(",",$entries[$counter - 2]);
                $groupid = $last_entry[0] + 1;
                $fp2 = fopen("userdata/$groupb", "a+");
                flock($fp2,2);
                fwrite($fp2,"".$groupid.",".encrypt($encrypter,$group).";");
                flock($fp2,3);
                fclose($fp2);
                begin_page(0,"",$stylesheet);
                echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
                echo $s_group . " \"<b>" . $group . "</b>\" " . $s_added;
                echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
                echo "</form>\n";
                end_page();
                exit;
            }
        }
    }
    if ($sort) {
        begin_page(0,"",$stylesheet);
        echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
        $fd = fopen ("userdata/$addressb", "r");
        $contents = fread($fd, filesize ("userdata/$addressb"));
        fclose ($fd);
        $allpeople = "";
        $entries = explode(";",$contents);
        for($j = 0; $j < count($entries); $j++) {
            $entries2 = explode(",",$entries[$j]);
            $entrie_name = strtolower(decrypt($encrypter,$entries2[0]));
            $sort_array[$entrie_name] = $j;
        }
        ksort ($sort_array);
        while (list ($key, $val) = each ($sort_array)) {
            $allpeople .= "".$entries[$val].";";
        }
        $fp3 = fopen("userdata/$addressb", 'w');
        fwrite($fp3,$allpeople);
        fclose($fp3);
        echo "$s_sorted<br>\n";
        echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
        echo "</form>\n";
        end_page();
        exit;
    }
    if ($email == $s_remove) {
        begin_page(0,"",$stylesheet);
        if ($delete){
            echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
            for ($i = 0; $i < sizeof($delete); $i++) {
                $fd = fopen("userdata/$addressb", "r");
                $contents = fread($fd, filesize ("userdata/$addressb"));
                fclose ($fd);
                $allpeople = "";
                $entries = explode(";",$contents);
                for($j = 0; $j < count($entries); $j++) {
                    $entries2 = explode(",",$entries[$j]);
                    if (encrypt($encrypter,strtolower($delete[$i])) == $entries2[1])
                        $found = "yes";
                    elseif ($entries2[1] != "")
                        $allpeople .= "".$entries[$j].";";
                }
                if ($found == "yes") {
                    $fp3 = fopen("userdata/$addressb", 'w');
                    fwrite($fp3,$allpeople);
                    fclose($fp3);
                    echo "$delete[$i] $s_removed.<br>\n";
                }
            }
            echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
            echo "</form>\n";
        }
        end_page();
        exit;
    }
    if ($email == $s_edit) {
        begin_page(0,"",$stylesheet);
        if (isset($old_name) && isset($old_email)) {
            echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
            $fd = fopen("userdata/$addressb", "r");
            $contents = fread($fd, filesize ("userdata/$addressb"));
            fclose ($fd);
            $allpeople = "";
            $found = FALSE;
            $entries = explode(";",$contents);
            for($j = 0; $j < count($entries); $j++) {
                $entries2 = explode(",",$entries[$j]);
                if (trim(decrypt($encrypter,$entries2[0])) == trim($old_name) && trim(decrypt($encrypter,$entries2[1])) == trim($old_email)) {
                    $found = TRUE;
                    $new_name = encrypt($encrypter,$new_name);
                    $new_email = encrypt($encrypter,$new_email);
                    $changed_entry = $new_name.",".$new_email.",".$new_group;
                    $allpeople .= "".$changed_entry.";";
                }
                elseif ($entries2[1] != "")
                    $allpeople .= "".$entries[$j].";";
                }
                if ($found) {
                    $fp3 = fopen("userdata/$addressb", 'w');
                    fwrite($fp3,$allpeople);
                    fclose($fp3);
                    echo $s_name . " \"<b>" . $old_name ."</b>\" ". $s_edited;
                }
        }
        echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
        echo "</form>\n";
        end_page();
        exit;
    }
    if ($group == $s_remove) {
        begin_page(0,"",$stylesheet);
        if ($deletegroup){
            echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
            for ($i = 0; $i < sizeof($deletegroup); $i++) {
                $fd = fopen("userdata/$groupb", "r");
                $contents = fread($fd, filesize ("userdata/$groupb"));
                fclose ($fd);
                $allgroups = "";
                $entries = explode(";",$contents);
                for($j = 0; $j < count($entries); $j++) {
                    $entries2 = explode(",",$entries[$j]);
                    if ($deletegroup[$i] == $entries2[0]) {
                        $found = "yes";
                        $groupname = decrypt($encrypter, $entries2[1]);
                    }
                    elseif ($entries2[0] != "")
                        $allgroups .= "".$entries[$j].";";
                }
                if ($found == "yes") {
                    $fp3 = fopen("userdata/$groupb", 'w');
                    fwrite($fp3,$allgroups);
                    fclose($fp3);
                    echo $s_group . " \"<b>" . $groupname . "</b>\" " . $s_removed . "<br>\n";
                }
            }
            echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
            echo "</form>\n";
        }
        end_page();
        exit;
    }
    if ($group == $s_edit) {
        begin_page(0,"",$stylesheet);
        if (isset($old_group_name)) {
            echo "<form action=\"$_SERVER[PHP_SELF]?address=true\" method=\"post\">\n";
            $fd = fopen("userdata/$groupb", "r");
            $contents = fread($fd, filesize ("userdata/$groupb"));
            fclose ($fd);
            $allgroups = "";
            $found = FALSE;
            $entries = explode(";",$contents);
            for($j = 0; $j < count($entries); $j++) {
                $entries2 = explode(",",$entries[$j]);
                if (decrypt($encrypter,$entries2[1]) == $old_group_name) {
                    $found = TRUE;
                    $new_group_name = encrypt($encrypter,$new_group_name);
                    $changed_entry = $entries2[0].",".$new_group_name;
                    $allgroups .= "".$changed_entry.";";
                }
                elseif ($entries2[1] != "")
                    $allgroups .= "".$entries[$j].";";
                }
                if ($found) {
                    $fp3 = fopen("userdata/$groupb", 'w');
                    fwrite($fp3,$allgroups);
                    fclose($fp3);
                    echo $s_group . " \"<b>" . $old_group_name ."</b>\" ". $s_edited;
                }
        }
        echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">\n";
        echo "</form>\n";
        end_page();
        exit;
    }
}
if ($address == "edit") {
    if (isset($addr_email)) {
        begin_page(0,"",$stylesheet);
        $found = FALSE;
        $fd = fopen("userdata/$addressb", "r");
        $contents = fread($fd, filesize ("userdata/$addressb"));
        fclose ($fd);
        $entries2 = explode(";",$contents);
        $fd1 = fopen("userdata/$groupb", "r");
        $contents1 = fread($fd1, filesize("userdata/$groupb"));
        fclose($fd1);
        $entries3 = explode(";",$contents1);
        for($i = 0; $i < count($entries2); $i++)
        {
            if ($entries2[$i] != "") {
                $entries = explode(",",$entries2[$i]);
                if (trim(decrypt($encrypter,$entries[1])) == trim($addr_email) && $found == FALSE) {
                    $found = TRUE;
                    echo "<br><form action=\"$_SERVER[PHP_SELF]?handling=address\" method=\"post\">";
                    echo "<table width=\"50%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n";
                    echo "<tr>\n";
                    echo "<td class=\"tableheader\">&nbsp;</td>\n";
                    echo "<td class=\"tableheader\">&nbsp;</td>\n";
                    echo "</tr>\n";
                    echo "<tr>\n";
                    echo "<td class=\"list\">$s_name: </td>\n";
                    echo "<td class=\"list\"><input type=\"text\" name=\"new_name\" size=\"30\" value=\"".decrypt($encrypter,$entries[0])."\">";
                    echo "</td>\n";
                    echo "</tr>\n";
                    echo "<tr>\n";
                    echo "<td class=\"list\">$s_email: </td>\n";
                    echo "<td class=\"list\"><input type=\"text\" name=\"new_email\" size=\"30\" value=\"$addr_email\"></td>\n";
                    echo "</tr>\n";
                    echo "<tr>\n";
                    echo "<td class=\"list\">$s_group: </td>\n";
                    echo "<td class=\"list\"><select name=\"new_group\">\n";
                    echo "<option value=\"0\">\n";
                    for($i = 0; $i < count($entries3); $i++)
                    {
                        if ($entries3[$i] != "") {
                            $entries4 = explode(",",$entries3[$i]);
                            echo "<option value=\"".$entries4[0]."\"";
                            if ($entries4[0] == $entries[2])
                                echo " selected ";
                            echo ">".decrypt($encrypter,$entries4[1])."\n";
                        }
                    }
                    echo "</select></td>\n";
                    echo "</tr>\n";
                    echo "</table>\n";
                    echo "<input type=\"hidden\" name=\"email\" value=\"$s_edit\">\n";
                    echo "<input type=\"hidden\" name=\"old_name\" value=\"".decrypt($encrypter,$entries[0])."\">\n";
                    echo "<input type=\"hidden\" name=\"old_email\" value=\"".$addr_email."\">\n";
                    echo "<input type=\"submit\" class=\"button\" value=\"$s_edit\">&nbsp;<input type=\"button\" class=\"button\" value=\"$s_clear\" onClick=\"javascript:history.back()\"></form>";
                }
            }
        }
        if ($found == FALSE)
            echo "$addr_email $s_not_found<br>";
        end_page();
        exit;
    }
    if (isset($group_name)) {
        begin_page(0,"",$stylesheet);
        $found = FALSE;
        $fd = fopen("userdata/$groupb", "r");
        $contents1 = fread($fd, filesize("userdata/$groupb"));
        fclose($fd);
        $entries3 = explode(";",$contents1);
        for($i = 0; $i < count($entries3); $i++)
        {
            if ($entries3[$i] != "") {
                $entries4 = explode(",",$entries3[$i]);
                if (decrypt($encrypter,$entries4[1]) == $group_name && $found == FALSE) {
                    $found = TRUE;
                    echo "<br><form action=\"$_SERVER[PHP_SELF]?handling=address\" method=\"post\">\n";
                    echo "<table width=\"50%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n";
                    echo "<tr>\n";
                    echo "<td class=\"tableheader\">&nbsp;</td>\n";
                    echo "<td class=\"tableheader\">&nbsp;</td>\n";
                    echo "</tr>\n";
                    echo "<tr>\n";
                    echo "<td class=\"list\">$s_group: </td>\n";
                    echo "<td class=\"list\"><input type=\"text\" name=\"new_group_name\" size=\"30\" value=\"".decrypt($encrypter,$entries4[1])."\">";
                    echo "</td>\n";
                    echo "</tr>\n";
                    echo "</table>\n";
                    echo "<input type=\"hidden\" name=\"group\" value=\"$s_edit\">\n";
                    echo "<input type=\"hidden\" name=\"old_group_name\" value=\"".decrypt($encrypter,$entries4[1])."\">\n";
                    echo "<input type=\"submit\" class=\"button\" value=\"$s_edit\">&nbsp;<input type=\"button\" class=\"button\" value=\"$s_clear\" onClick=\"javascript:history.back()\"></form>";
                }
            }
        }
        if ($found == FALSE)
            echo "$group_name $s_not_found<br>";
        end_page();
        exit;
    }
}
?>