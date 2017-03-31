<?php

if ($handling == "send" && $send){
    begin_page(0,"",$stylesheet);
    echo "<form action=\"$_SERVER[PHP_SELF]?handling=write\" method=\"post\">";
    if (strstr($toText, $s_group)) {
        $toArray = explode(", ", $toText);
        $counter = 0;
        $fd = fopen ("userdata/$addressb", "r");
        $addr_contents = fread ($fd, filesize ("userdata/$addressb"));
        fclose ($fd);
        $addr_entries = explode(";",$addr_contents);

        $tmp = false;
        while ($counter < count($toArray)) {
            if (substr($toArray[$counter], 0, strlen($s_group)) == $s_group) {
                $groupNumber = substr($toArray[$counter], -1);
                $toExtra = "";
                for($i = 0; $i < count($addr_entries); $i++) {
                    if ($addr_entries[$i] != "") {
                        $entries2 = explode(",",$addr_entries[$i]);
                        if ($entries2[2] == $groupNumber){
                            $toExtra .= decrypt($encrypter, $entries2[1]).", ";
                        }
                    }
                }
                if(!$tmp) {
                    $temp = ereg_replace($toArray[$counter], $toExtra, $toText);
                    $tmp = true;
                }
                else $temp = ereg_replace($toArray[$counter], $toExtra, $temp);
            }
            $counter++;
        }
        $toText = ereg_replace(", ,", ",", $temp);
    }

    if ($useSMTPauth == 1){
        include "prog_maillib_auth.php";
    }
    else
    {
        include "prog_maillib.php";
    }
    echo "<br><br><input type=\"submit\" class=\"button\" value=\"$s_ok\">";
    echo "</form>";
    if($notification == "sent"){
        echo"<script type=\"text/javascript\">\n<!--\nwindow.close();\n//-->\n</script>\n";
    }
    end_page();
    exit;
}
if ($handling == "send" && !$send){
    $handling = "write send";
}
if ($handling == "write" || $handling == "write send") {
    begin_page(1,"",$stylesheet);
    if($notification) {
        $notification = htmlspecialchars(preg_replace("/&lt;(.*)&gt;.*/i", "\$1", $notification));
        $subject = $s_writeReadNotif .": " .$notif_subject;
        $notif_text = preg_replace("/MAILADDRESS/", "$notification", $s_notif_text);
        $notif_text = preg_replace("/SUBJECT/", "$notif_subject", $notif_text);
        $date = date("d.m.Y",time());
        $time = date("H:i",time());
        $notif_text = preg_replace("/DATE/", "$date", $notif_text);
        $notif_text = preg_replace("/TIME/", "$time", $notif_text);
        if (strstr($notification, "&lt;") && strstr($notification, "&gt;")){
            list($name, $email) = split("&lt;", $notification);
            $smtpTo = substr($email, 0, -4);
        }
        else $smtpTo = $notification;
        echo "<form name=\"compose\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\" method=\"post\">\n";
        echo "<center> <table border=\"0\">
            <tr>
              <td class=\"list\" colspan=\"2\">$s_notification</td>
            </tr>
            <tr>
              <td class=\"list\" width=\"50\">$s_mTo:</td>
              <td class=\"list\"><input type=\"hidden\" name=\"toText\" size=\"40\" value=\"$smtpTo\">".stripslashes($notification)."</td>
              <input type=\"hidden\" name=\"fromText\" value=\"$mailaddress\">
              <input type=\"hidden\" name=\"notification\" value=\"sent\">
              <input type=\"hidden\" name=\"priority\" value=\"3\">
              </td>
            </tr>
            <tr>
              <td class=\"list\" width=\"50\">$s_writeSubject:</td>
              <td class=\"list\"><input type=\"hidden\" name=\"subjectText\" value=\"$subject\">$subject</td>
            </tr>
            <tr>
              <td class=\"list\" width=\"50\" valign=\"top\">$s_writeMessage:</td>
              <td class=\"list\"><textarea rows=\"5\" name=\"msgText\" cols=\"40\">".stripslashes($notif_text)."</textarea></td>
            </tr>
            <tr>
              <td class=\"list\" align=\"center\" colspan=\"3\">
              <input type=\"submit\" class=\"button\" name=\"send\" value=\"$s_send\">&nbsp;&nbsp;&nbsp;
              <input type=\"button\" class=\"button\" value=\"$s_dont_send\" onClick=\"javascript:self.close()\">
              </td>
            </tr>
            </table>
            <input type=\"hidden\" name=\"handling\" value=\"send\"> </center>
            </form>\n";
        end_page();
        exit;
    }
    if($handling == "write send") {
        $reply_to = $toText;
        $fixedSender = $fromText;
        $subject = $subjectText;
        $ebrev = $msgText;

        $key = "0";
        if($File_name){
          $key_2 = "0";
          foreach($File_name as $key => $value){
              if($File_att[$key]){
                  $Attachements .= "                <input type=\"hidden\" name=\"File_name[$key_2]\" value=\"".$File_name[$key]."\">\n";
                  $Attachements .= "                <input type=\"checkbox\" name=\"File_att[$key_2]\" value=\" checked\"".$File_att[$key].">".$File_name[$key]."<br>\n";
                  $key_2 = $key_2 + 1;
                  $files_temp[$File_name[$key]] = $Files_attached[$File_name[$key]];
              }
          }
        }
        if($fil){
            if(ini_get('register_globals')) {
                $fil_tmp_name = $fil;
                $fil = array();
                $fil["size"] = $fil_size;
                $fil["name"] = $fil_name;
                $fil["tmp_name"] = $fil_tmp_name;
            }
            if($fil["size"] > 0){
                $Attachements .= "                <input type=\"hidden\" name=\"File_name[$key_2]\" value=\"".$fil["name"]."\">\n";
                $Attachements .= "                <input type=\"checkbox\" name=\"File_att[$key_2]\" value=\" checked\" checked>".$fil["name"]."<br>\n";
                $filename = $fil["tmp_name"];
                $fhandle = fopen ($filename, "rb");
                $contents = fread ($fhandle, filesize ($filename));
                fclose ($fhandle);
                $files_temp[$fil["name"]] = $contents;
            }
            else{
                $Attachements .= "error uploading file: ".$fil["name"];
            }
        }
        if(ini_get('register_globals')) {
            $Files_attached = $files_temp;
            session_register("Files_attached");
        }
        else {
            $_SESSION["Files_attached"] = $files_temp;
        }
    }
    else {
        $ebrev = htmlspecialchars(stripslashes($ebrev));
        $fixedSender = htmlspecialchars($fixedSender);
        $signature = htmlspecialchars($signature);
        $subject = htmlspecialchars(chop($title));
        if ($reply) {
            $reply_to = preg_replace("/.*<(.*)>/i", "\$1", $sendTo);
            $subject = "Re: $title";
            $sendTo = htmlspecialchars(chop($sendTo));
            $sendTo = preg_replace("/.&quot;/", "&quot;", $sendTo);
            $ebrev = str_replace("\n", "\n> ", $ebrev);
            $ebrev = "$sendTo $s_wrote\n>\n> $ebrev";
        }
        if ($forward) {
            $subject = "Fwd: $title";
            $ebrev = "\n".$signature."\n\n\n"."-------------------- $s_forwarded --------------------\n\n$ebrev";
            $sendTo = "";
        }
        else{
            $ebrev = "\n".$signature."\n\n\n".$ebrev;
        }
        if (!$reply && !$forward) {
            $reply_to = $sendTo;
        }
        if ($reply_to == null){
            $reply_to = $sendTo;
        }
        if (!$fixedSender) {
            $fixedSender = $mailaddress;
        }
    }
    echo "<form name=\"compose\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\" method=\"post\">\n";
    echo "<table border=\"0\" width=\"90%\">
          <tr>
            <td class=\"list\" width=\"12%\">$s_mTo:</td>
            <td class=\"list\" width=\"45%\"><input type=\"text\" name=\"toText\" size=\"40\" value=\"$reply_to\"></td>
            <td class=\"list\" rowspan=\"10\" align=\"center\">
              <select name=\"desttoadd\" size=\"22\">\n";
              if (filesize("userdata/$addressb") != NULL) {
                  $fd = fopen ("userdata/$addressb", "r");
                  $contents = fread ($fd, filesize ("userdata/$addressb"));
                  fclose ($fd);
                  $entries = explode(";",$contents);
                  for($i = 0; $i < count($entries); $i++) {
                    if ($entries[$i] != "") {
                      $entries2 = explode(",",$entries[$i]);
                      echo "                <option value=\"".decrypt($encrypter,$entries2[1])."\">".decrypt($encrypter,$entries2[0])."\n";
                    }
                  }
              }
              if (filesize("userdata/$groupb") != NULL) {
                  $fd = fopen ("userdata/$groupb", "r");
                  $contents = fread ($fd, filesize ("userdata/$groupb"));
                  fclose ($fd);
                  $entries = explode(";",$contents);
                  if (count($entries) > 1)
                      echo "                <option value=\"\">-----------\n";
                  for($i = 0; $i < count($entries); $i++) {
                    if ($entries[$i] != "") {
                      $entries2 = explode(",",$entries[$i]);
                      echo "                <option value=\"$s_group ".$entries2[0]."\" style=\"font-weight: bold;\">".decrypt($encrypter,$entries2[1])."\n";
                    }
                  }
              }
        echo "              </select><br>
              <center>
              <input class=\"button\" name=\"adddest\" type=\"button\" value=\"&nbsp;$s_mTo&nbsp;\" onClick=\"addTo(document.compose.desttoadd, document.compose.toText)\">&nbsp;
              <input class=\"button\" name=\"addcc\" type=\"button\" value=\"&nbsp&nbsp;$s_writeCc&nbsp;&nbsp;\" onClick=\"addTo(document.compose.desttoadd, document.compose.ccText)\">&nbsp;
              <input class=\"button\" name=\"addcci\" type=\"button\" value=\"&nbsp;$s_writeBcc&nbsp;\" onClick=\"addTo(document.compose.desttoadd, document.compose.bccText)\">
              </center>
              </td>
            </tr>
            <tr>
              <td class=\"list\" width=\"12%\">$s_writeCc:</td>
              <td class=\"list\" width=\"45%\"><input type=\"text\" name=\"ccText\" size=\"40\" value=\"$ccText\"></td>
            </tr>
            <tr>
              <td class=\"list\" width=\"12%\">$s_writeBcc:</td>";
        if (!empty($autobcc)) {
            $bccText = $autobcc . $bccText;
        }
        echo "      <td class=\"list\" width=\"45%\"><input type=\"text\" name=\"bccText\" size=\"40\" value=\"$bccText\"></td>
            </tr>
            <tr>
              <td class=\"list\" width=\"14%\">$s_writeFrom:</td>
              <td class=\"list\">";
        if (!empty($fixedSender) && $changeableSender != "yes"){
            echo "<input type=\"hidden\" name=\"fromText\" size=\"40\" value=\"$fixedSender\">$fixedSender";
        }
        else{
            echo "<input type=\"text\" name=\"fromText\" size=\"40\" value=\"$fixedSender\">";
        }
        echo "</td>
            </tr>
            <tr>
              <td class=\"list\" width=\"14%\">$s_priority:</td>
              <td class=\"list\"><select name=\"priority\">
                <option value=\"1\">$s_priority1
                <option value=\"2\">$s_priority2
                <option value=\"3\" selected>$s_priority3
                <option value=\"4\">$s_priority4
                <option value=\"5\">$s_priority5
                </select>\n";
              echo "              </td>
            </tr>
            
            <tr>
              <td class=\"list\" width=\"14%\">$s_writeAttach:
                <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"2097152\"></td>
              <td class=\"list\">\n$Attachements
                <input type=\"file\" name=\"fil\" size=\"40\" maxlength=\"2097152\" onChange=\"javascript:document.compose.submit();\">
              </td>
            </tr>
            <tr>
              <td class=\"list\" width=\"14%\">$s_writeSubject:</td>
              <td class=\"list\"><input type=\"text\" name=\"subjectText\" size=\"40\" value=\"$subject\"></td>
            </tr>
            <tr>
              <td class=\"list\" width=\"14%\" valign=\"top\">$s_writeMessage:</td>
              <td class=\"list\"><textarea rows=\"$msg_rows\" name=\"msgText\" cols=\"$msg_cols\">$ebrev</textarea></td>
            </tr>
            <tr>
              <td class=\"list\" align=\"center\" colspan=\"3\">
                <input type=\"submit\" class=\"button\" name=\"send\" value=\"$s_send\">&nbsp;&nbsp;&nbsp;
                <input type=\"reset\" value=\"$s_clear\" class=\"button\">
              </td>
            </tr>
          </table>
          <!--input type=\"hidden\" name=\"handling\" value=\"vars\"-->
          <input type=\"hidden\" name=\"handling\" value=\"send\">
          </form>\n";
          end_page();
}
?>