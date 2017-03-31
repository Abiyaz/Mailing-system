<?php
if (!$debug){$debug = "0";}

if ($frame == "inbox") {
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> inbox -> START\n";
        fwrite($flow_error_debug, $flow_text);
    }
    if ($msglistpage == 0) {
	    $msglistpage = 1;
	}
    if($delete_mails != "refresh"){
        begin_page("", "inbox",$stylesheet);
    }
    if ($deleteMessage || $delete_mails == "refresh") {
        if ($copy_mails || $copy_mails_top){
            if($copy_mails_top){
                $copy_mails = $copy_mails_top;
                $mail_aktion = $mail_aktion_top;
                $to_folder = $to_folder_top;
            }

            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $msg_nrs = join(", ",$deleteMessage);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> copy mails from $mode to $to_folder (get mails) -> START\n   -> MSG Nr: $msg_nrs\n";
                fwrite($flow_error_debug, $flow_text);
            }
            $type = "compl";
            $mail_copy = array();
            foreach($deleteMessage as $mailNumber){
                if (!$mode){
                    include("prog_mail_pop.php");
                }elseif ($mode){
                    $link_add = "&amp;mode=$mode";
                    include("prog_mail_file.php");
                }
                array_push($mail_copy, $mail_compl);
            }
            echo " ";
            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $msg_nrs = join(", ",$deleteMessage);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> copy mails from $mode to $to_folder (add to file) -> START\n";
                fwrite($flow_error_debug, $flow_text);
            }
            $type = "add";
            include("prog_mail_file.php");

            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $msg_nrs = join(", ",$deleteMessage);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> copy mails from $mode to $to_folder (add to file) -> STOP\n";
                fwrite($flow_error_debug, $flow_text);
            }
            echo " ";
            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $msg_nrs = join(", ",$deleteMessage);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> copy mails from $mode to $to_folder (remove mails) -> START\n";
                fwrite($flow_error_debug, $flow_text);
            }
            if ($mail_aktion == "move"){
                $type = "del";
                if (!$mode){
                    include("prog_mail_pop.php");
                }elseif ($mode){
                    $link_add = "&amp;mode=$mode";
                    include("prog_mail_file.php");
                }
            }

            echo "<form action=\"$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=$msglistpage$link_add\" method=post>\n";
            echo "$s_messagescopyed<br><br>\n";
            echo "<input type=submit class=button name=sort_mails value=\"$s_ok\">\n";
            echo "<input type=hidden name=deleteMessage value=\"$to_folder\">\n";
            echo "</form>\n";
            end_page();
            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $msg_nrs = join(", ",$deleteMessage);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> copy mails from $mode to $to_folder (remove mails) -> STOP\n";
                fwrite($flow_error_debug, $flow_text);
            }
            exit;
        }
        if ($sort_mails){
            $to_folder = $deleteMessage;
            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> sort mails in $to_folder -> START\n";
                fwrite($flow_error_debug, $flow_text);
            }
            $type = "sort";
            include("prog_mail_file.php");
            echo "<meta http-equiv=\"refresh\" content=\"1;url=$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=$msglistpage$link_add\">";
            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> sort mails in $to_folder -> STOP\n";
                fwrite($flow_error_debug, $flow_text);
            }
            exit;
        }
        if ($delete_mails){
            if($delete_mails == "refresh"){
                echo "<meta http-equiv=\"refresh\" content=\"1;url=$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=$msglistpage&amp;mode=$mode\">";
                exit;
            }
            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $msg_nrs = join(", ",$deleteMessage);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> delete mails in $mode -> START\n   -> MSG Nr: $msg_nrs\n";
                fwrite($flow_error_debug, $flow_text);
            }
            $type = "del";
			$deleteMessage = $deleteMessage;
            if (!$mode){
                include("prog_mail_pop.php");
            }elseif ($mode){
                $link_add = "&amp;mode=$mode";
                include("prog_mail_file.php");
            }

        echo "<form action=\"$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=$msglistpage$link_add&amp;delete_mails=refresh\" method=post>\n";
        echo "$s_messagesDeleted<br><br>\n";
        echo "<input type=submit class=button value=\"$s_ok\">\n";
        echo "</form>\n";
        end_page();
        if($flow_contr == "1"){
            $time_now = time();
            $time_stamp = $time_now - $flow_time;
            $time_stamp = substr("000$time_stamp", -3);
            $msg_nrs = join(", ",$deleteMessage);
            $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> delete mails in $mode -> STOP\n";
            fwrite($flow_error_debug, $flow_text);
        }
        exit;
        }
    }
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> get mail list for $mode -> START\n";
        fwrite($flow_error_debug, $flow_text);
    }
    $type = "stat";
    if (!$mode){
        $mode_print = "<b>&lt;&nbsp;POP3 Server&nbsp;&gt;</b>\n";
        $folder_split = split("\n", $folder);
        foreach($folder_split as $foldername){
            $foldername = chop($foldername);
            if ($foldername == ""){ continue; }
            $mode_print .= "<a href=\"$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=1&amp;mode=$foldername\" target=inbox>&lt;$foldername&gt;</a>\n";
        }
        include("prog_mail_pop.php");
    }elseif ($mode){
        $link_add = "&amp;mode=$mode";
        $mode_print = "<a href=\"$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=1\" target=inbox>&lt;POP3 Server&gt;</a>\n";
        $folder_split = split("\n", $folder);
        foreach($folder_split as $foldername){
            $foldername = chop($foldername);
            if ($foldername == ""){ continue; }
            if ($foldername == $mode){ $mode_print .= "<b>&lt;&nbsp;$foldername&nbsp;&gt;</b> "; }
            else { $mode_print .= " <a href=\"$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=1&amp;mode=$foldername\" target=inbox>&lt;$foldername&gt;</a> \n"; }
        }
        include("prog_mail_file.php");
    }
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> get mail list for $mode -> STOP\n";
        fwrite($flow_error_debug, $flow_text);
    }
    echo "<script language=\"javascript\" type=\"text/javascript\">\n";
    echo "<!--\n";
    echo "function deleteMail(selection) {\n";
    echo "  for (i = 0; i < document.forms[0].elements.length; i++) {\n";
    echo "      if (selection == \"all\") {\n";
    echo "          document.forms[0].elements[i].checked = true\n";
    echo "      }\n";
    echo "      else {\n";
    echo "          document.forms[0].elements[i].checked = false\n";
    echo "      }\n";
    echo "  }\n";
    echo "}\n";
    echo "// -->\n";
    echo "</script>\n";
    echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";
    echo "<!--\n";
    echo "function MM_openBrWindow(theURL,winName,features) { //v2.0\n";
    echo "  Fenster = window.open(theURL,winName,features);\n";
    echo "  Fenster.moveTo(screen.width-410,0);\n";
    echo "  Fenster.focus();\n";
    echo "}\n";
    echo "//-->\n";
    echo "</script>\n\n";
    echo "<form action=\"$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=$msglistpage$link_add\" method=post>\n";

    if ($ListMessages != 0){
    	$msg_list_start = $antalEbreve - (($msglistpage - 1) * $ListMessages);
    	$msg_list_last = $msg_list_start - $ListMessages + 1;
    	if ($msg_list_last <=1){
    		$msg_list_last = 1;
    	}
        echo "<table>\n";
        $vmbox_info = "<a href=\"javascript:MM_openBrWindow('$_SERVER[PHP_SELF]?frame=folder_info','info','scrollbars=yes,width=400,height=500');\">";
        echo "<tr>\n<td><b>$vmbox_info$s_source</a></b>\n<td>$mode_print";
    	echo "<tr>\n<td><b>$s_ListPages</b><td>\n";
    	$nr_of_pages = round(($antalEbreve /  $ListMessages) + 0.5);
        if ($antalEbreve == ($nr_of_pages - 1) * $ListMessages){ $nr_of_pages = $nr_of_pages - 1;}
        if ($antalEbreve == 0) { $nr_of_pages = 1; }
    	for ($l = 1; $l <= $nr_of_pages; $l++){
    		$tag_start = "";
    		$tag_stop = "";
    		$tag_sep = "";
    		if ($l == $msglistpage) {
    		    $tag_start = "<b>";
    		    $tag_stop = "</b>";
    		    $tag_sep = "&nbsp;";
    		}
    		echo "<a href=\"$_SERVER[PHP_SELF]?frame=inbox&amp;msglistpage=$l$link_add\" target=inbox>$tag_start&lt;$tag_sep$l$tag_sep&gt;$tag_stop</a>\n";
    	}
        echo "</table>\n\n";
    }else{
    	$msg_list_start = $antalEbreve;
    	$msg_list_last = 1;
        echo "<table>\n";
        $vmbox_info = "<a href=\"javascript:MM_openBrWindow('$_SERVER[PHP_SELF]?frame=folder_info','info','scrollbars=yes,width=400,height=500');\">";
        echo "<tr>\n<td><b>$vmbox_info$s_source</a></b>\n<td>$mode_print";
        echo "</table>\n\n";
    }
    if ($antalEbreve == 0) {
        echo "<br><br> $s_noEmails ";
        end_page();
        if($flow_contr == "1"){
            $time_now = time();
            $time_stamp = $time_now - $flow_time;
            $time_stamp = substr("000$time_stamp", -3);
            $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> no mails in $mode -> STOP\n";
            fwrite($flow_error_debug, $flow_text);
        }
        exit;
    }

    echo "<br> \n <center>";
    echo "<input type=button class=button value=\"$s_selectAll\" onClick=\"deleteMail('all')\">\n";
    echo "<input type=button class=button value=\"$s_deselectAll\" onClick=\"deleteMail('none')\">\n";
    echo "<input type=submit class=button name=delete_mails value=\"$s_delete\">\n";
    echo " &nbsp; &nbsp; &nbsp; \n";
    echo "<select name=mail_aktion_top >\n";
    echo "<option value=\"copy\" >$s_copy\n";
    echo "<option value=\"move\" >$s_move\n";
    echo "</select>\n";
    echo "<select name=to_folder_top ></center>\n";
    $folder_split = split("\n", $folder);


    foreach($folder_split as $foldername){
        $foldername = chop($foldername);
        if ($foldername == "" || $foldername == $mode){ continue; }
        echo "<option value=\"$foldername\" >$foldername\n";
    }
    echo "</select>\n";
    echo "<input type=submit class=button name=copy_mails_top value=\"$s_ok\">\n";
    echo "<table width=\"100%\" border=0 cellspacing=1 cellpadding=2>\n";
    echo "<tr>\n";
    echo "<td class=tableheader>&nbsp;</td>\n";
    echo "<td class=tableheader>&nbsp;</td>\n";
    echo "<td class=tableheader> &nbsp; &nbsp; </td>\n";
    echo "<td class=tableheader>$s_subject</td>\n";
    echo "<td class=tableheader>$s_from</td>\n";
    echo "<td class=tableheader>$s_date</td>\n";
    echo "<td class=tableheader>$s_size</td>\n";
    echo "</tr>\n";
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> get mail header for $mode -> START\n";
        fwrite($flow_error_debug, $flow_text);
    }
    for ($i = $msg_list_start; $i >= $msg_list_last; $i--) {
        $subject = "";
        $fra = "";
        $dato = "";
        $prior = "";
        $status = "";
        $stoerrelse = "";
        $attachement = "<td class=list>&nbsp;";
        $prior = 3;
        $type = "head";
        $mailNumber = $i;
        if (!$mode){
            include("prog_mail_pop.php");
        }elseif ($mode){
            $link_add = "&amp;mode=$mode";
            include("prog_mail_file.php");
        }

        $header_lines = split("\n", $header);
        foreach($header_lines as $line){
            if (substr($line, 0, 9) == "Subject: "){
                $subject = htmlspecialchars(mimeHeaderDecode(substr($line, 9)));
            }
            if (substr($line, 0, 6) == "From: ") {
                $fra_line = htmlspecialchars(mimeHeaderDecode(substr($line, 6)));
                list($fra_name,$fra_addr) = split(" *&lt;",$fra_line);
                $fra_name = preg_replace("/.*&quot;(.*)&quot;/", "\$1", $fra_name);
                $fra_addr = preg_replace("/(.*)&gt;.*/", "\$1", $fra_addr);
                if(strlen($fra_name) == 0){
                    $fra = $fra_addr;
                }else{
                    $fra = $fra_name;
                }
            }
            if (substr($line, 0, 4) == "To: ") {
                $to_line = htmlspecialchars(mimeHeaderDecode(substr($line, 4)));
                list($to_name,$to_addr) = split(" *&lt;",$to_line);
                $to_name = preg_replace("/.*&quot;(.*)&quot;/", "\$1", $to_name);
                $to_addr = preg_replace("/(.*)&gt;.*/", "\$1", $to_addr);

                if(strlen($to_name) == 0){
                    $to = $to_addr;
                }else{
                    $to = $to_name;
                }
            }
            if (preg_match("/^Date: /i", $line)){
                $dato = htmlspecialchars(substr($line, 6));
                $dato = preg_replace("/\(.*\)/i", "", $dato);
            }
            if (preg_match("/^X-Priority: /i", $line)){
                $prior = htmlspecialchars(substr($line, 12, 1));
            }
            if (preg_match("/boundary/i", $line)){
                $attachement = "<td class=list><img src=\"images/att.gif\" border=\"0\" alt=\"attachement\">";
            }
            if (preg_match("/^Status: /i", $line)){
                $status = htmlspecialchars(substr($line, 8, 1));
            }
        }
	if($fra_addr == ""){ $fra_addr = $fra; }
	if($debug >= "1"){echo " comp $fra_addr == $mailaddress <br>\n";}
	$sent_add = "";
	if($fra_addr == $mailaddress){
	    $fra = $to;
	    $sent_add = "_sent";
	}
        $type = "size";
        $mailNumber = $i;
        if (!$mode){
            include("prog_mail_pop.php");
        }elseif ($mode){
            $link_add = "&amp;mode=$mode";
            include("prog_mail_file.php");
        }

        if ($debug >= "1"){echo " -- $i -- $subject\n<br>\n\n\n";}
        if (preg_match("/^[\s]*$/i", $subject)){$subject = "$s_nosubject";}
        if ($max_sub_len != 0){
           if (strlen($subject) > $max_sub_len){ $subject = substr($subject, 0, ($max_sub_len - 4))." ...";}
        }
        if (!$subject || $subject == "\r\n")
            $subject = "&nbsp;";
        if (!$dato || $dato == "\r\n")
            $dato = "&nbsp;";
        if (!$fra || $fra == "\r\n")
            $fra = "&nbsp;";
        echo "<tr>\n<td class=list><input type=checkbox name=\"deleteMessage[]\" value=$i></td>\n";
        echo "<td class=list>";
        if ($status == "R") {
            echo "<img src=\"images/read".$sent_add.".gif\" border=\"0\" alt=\"read".$sent_add."\"></td>\n$attachement</td>\n";
            $new_start = "";
            $new_stop = "";
        }
        else {
            echo "<img src=\"images/unread".$sent_add.".gif\" border=\"0\" alt=\"unread".$sent_add."\"></td>\n$attachement</td>\n";
            $new_start = "<b>";
            $new_stop = "</b>";
        }
        echo "<td class=list><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$i$link_add\" target=bottom style=\"text-decoration: none; color: black\">";
        if ($prior == 1 || $prior == 2)
            echo "<img src=\"images/high.gif\" border=\"0\">";
        if ($prior == 4 || $prior == 5)
            echo "<img src=\"images/low.gif\" border=\"0\">";
        echo "$new_start$subject$new_stop</a></td>\n";
        echo "<td class=list><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$i$link_add\" target=bottom style=\"text-decoration: none; color: black\">$new_start$fra$new_stop</a></td>\n";
        echo "<td class=list><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$i$link_add\" target=bottom style=\"text-decoration: none; color: black\">$new_start$dato$new_stop</a></td>\n";
        echo "<td class=list><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$i$link_add\" target=bottom style=\"text-decoration: none; color: black\">$new_start$stoerrelse$new_stop</a></td>\n</tr>\n";
    }
    if($flow_contr == "1"){
        $time_now = time();
        $time_stamp = $time_now - $flow_time;
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> get mail header for $mode -> STOP\n";
        fwrite($flow_error_debug, $flow_text);
    }
    echo "</table>\n <center>";
    echo "\n";
    echo "<input type=button class=button value=\"$s_selectAll\" onClick=\"deleteMail('all')\">\n";
    echo "<input type=button class=button value=\"$s_deselectAll\" onClick=\"deleteMail('none')\">\n";
    echo "<input type=submit class=button name=delete_mails value=\"$s_delete\">\n";
    echo " &nbsp; &nbsp; &nbsp; \n";
    echo "<select name=mail_aktion >\n";
    echo "<option value=\"copy\" >$s_copy\n";
    echo "<option value=\"move\" >$s_move\n";
    echo "</select>\n";
    echo "<select name=to_folder >\n</center>";
    $folder_split = split("\n", $folder);
    foreach($folder_split as $foldername){
        $foldername = chop($foldername);
        if ($foldername == "" || $foldername == $mode){ continue; }
        echo "<option value=\"$foldername\" >$foldername\n";
    }
    echo "</select>\n";
    echo "<input type=submit class=button name=copy_mails value=\"$s_ok\">\n";
    echo "</form>\n";
    end_page();
}
?>