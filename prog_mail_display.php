<?php

$ext_arry .=  "jpg jpeg gif pcx ";		
$ext_arry .= "html htm pl php cgi ";			
$ext_arry .= "pdf doc xls ppt pps csv ";

if ($showSource && $mailNumber) {
    $type = "compl";                                     
    $mailNumber = $mailNumber;                           
    if (!$mode){
        include("prog_mail_pop.php");                    
    }elseif ($mode){
        $link_add = "&mode=$mode";
        include("prog_mail_file.php");	                 
    }

    begin_page(0,"",$stylesheet);
    echo "\n<pre>\n";
    echo htmlspecialchars($mail_compl);
    echo "\n</pre>\n";
    end_page();
    exit;
}

if ($showNormalHeaders){
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> prog_mail_display.php -> Start of View Mail (1) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    $flow_text .= "$time_stamp >>> prog_mail_display.php -> Start of View Mail (1) -> MemoryUsage Peak: " . memory_get_peak_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
    $link_add = "";
    $type = "compl";                                     
    $mailNumber = $showNormalHeaders;                    
    if (!$mode){
        include("prog_mail_pop.php");                   
    }elseif ($mode){
        $link_add = "&mode=$mode";
        include("prog_mail_file.php");                  
    }
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> prog_mail_display.php -> After requesting mail (2) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    $flow_text .= "$time_stamp >>> prog_mail_display.php -> Requested Mail size (4) -> Mail ($mailNumber): " . strlen($mail_compl) . " Bytes\n";
	    $flow_text .= "$time_stamp >>> prog_mail_display.php -> After requesting mail (2) -> MemoryUsage Peak: " . memory_get_peak_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
    $split_temp = preg_split ("/\n\n/", $mail_compl);
    unset($mail_compl);

    $header = array_shift ($split_temp);
    $body = join ("\n\n", $split_temp);
    unset($split_temp);
    $lines = split ("\n", $header);

	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> prog_mail_display.php -> Check header line by line -> START\n";
	    fwrite($flow_error_debug, $flow_text);
	}
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> prog_mail_display.php -> After mail splitting (3) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    $flow_text .= "$time_stamp >>> prog_mail_display.php -> After mail splitting (3) -> MemoryUsage Peak: " . memory_get_peak_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
    foreach($lines as $line){
        if (preg_match("/boundary=/i", $line)){
            $boundary = preg_replace('/^.*boundary=(.*) *.*/i', "\$1", chop($line));
            $boundary = preg_replace('/"(.*)".*/', "\$1", $boundary);
            if($debug >= "3"){echo "BOUNDARY=|$boundary|\n<br>\n\n";}    
        }
        if(preg_match("/Content-Type:\smultipart\/alternative/i", $line)){
        	$multi_split = "1";
        }

    }
    unset($lines);
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> prog_mail_display.php -> After header splitting (4) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    $flow_text .= "$time_stamp >>> prog_mail_display.php -> After header splitting (4) -> MemoryUsage Peak: " . memory_get_peak_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
    if ($boundary){
       list($body) = split ("--$boundary--", $body);
       $parts = split ("--$boundary", $body);
    }
    else{
       $parts[0] = $body;
       include("filter/application_content.php");
       $parts[0] = appl_content($parts[0], $header);
    }
    unset($body);

    if ($attmail){
       $attmail_s = $attmail-1;
       $mail_content_array = preg_split("/\n\n/", $parts[$attmail_s]);
       $trash = array_shift ($mail_content_array);
       $mail_content = join ( "\n\n", $mail_content_array);
       $sub_mail = sub_mail($mail_content, $attmail);
    }
    $nr_parts = count($parts);
    if($debug >= "3"){echo "Nr. of Parts: $nr_parts\n<br>\n\n";}     
    for ($nr = 0; $nr <= $nr_parts - 1; $nr++){
        $part_split = preg_split("/[\r,\n]\n/", $parts[$nr]);
        $part_header[$nr] = array_shift ($part_split);
        $part_body[$nr] = join ( "\n\n", $part_split);
        $part_header[$nr] = preg_replace ("/\n\s/", " ", $part_header[$nr]);
        if (preg_match("/Content-Transfer-Encoding/", $part_header[$nr])) {
            $temp_part_header = join(" ",preg_split("/\n/",$part_header[$nr]));
            $content_transfer[$nr] = preg_replace("/.*(Content-Transfer-Encoding: .*)\s*.*$/", "\$1", $temp_part_header);
            unset($temp_part_header);
        }
        if(!preg_match("/Content-Type: /i", $part_header[$nr])){
           $part_body[$nr] = $part_header[$nr]."\n\n".$part_body[$nr];
           $part_header[$nr] = "";
        }
        if($debug >= "3"){                                                                      
        	echo "----------<br>\n".$part_header[$nr]."\n<br>\n";                           
        	echo "<textarea rows=\"11\" cols=\"70\">".$part_body[$nr]."</textarea>\n";     
        	echo "<textarea rows=\"11\" cols=\"70\">".$parts[$nr]."</textarea>\n<br>\n\n";  
        }                                                                                       
        if(preg_match("/Content-Type:\smultipart\/alternative/i", $part_header[$nr])){
           $multi_split = "1";
        }
        unset($part_split);
    }
    unset($parts);
    if($multi_split){
       include("filter/filter_outlook.php");
    }
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> prog_mail_display.php -> After analysing mail parts (5) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    $flow_text .= "$time_stamp >>> prog_mail_display.php -> After analysing mail parts (5) -> MemoryUsage Peak: " . memory_get_peak_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
    if ($showNormalHeaders && $part){
    	$part = $part - 1;
        $header_temp = preg_replace("/\n/", "; ", $part_header[$part]);
        $header_cont = split("; ", $header_temp);
        unset($header_temp);
        foreach ($header_cont as $line ){
           if (preg_match("/^Content-Type: /i", $line)){
               $content_type = trim($line);
           }
           if (preg_match("/filename=/i", $line)){
               $filename = mimeHeaderDecode(trim($line));
           }
           if (preg_match("/^Content-Transfer-Encoding: /i", $line)){
               $content_encoding = trim($line);
           }
        }

        if(preg_match("/quoted-printable/i", $content_encoding)){
           $part_body[$part] = quoted_printable_decode($part_body[$part]);
        }
        elseif(preg_match("/base64/i", $content_encoding)){
           $part_body[$part] = base64_decode($part_body[$part]);
        }
        if (preg_match("/text\/html/i", $content_type)){
           include("filter/filter_outlook_html.php");
        }
        if(preg_match("/message\//", $content_type)){
        	$content_type = "Content-Type: text/plain";
        }
        header("$content_type");
        header("Content-Disposition: $filename");
        echo $part_body[$part];
        exit;
    }
    if($GLOBALS[flow_contr] == "1"){
        $time_now = time();
        $time_stamp = $time_now - $GLOBALS[flow_time];
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_display.php -> interpret message nr. $mailNumber -> START \n";
        fwrite($GLOBALS[flow_error_debug], $flow_text);
    }
    $time_now = time();
    $time_diff = $time_now - $GLOBALS[flow_time];
    if ($showNormalHeaders && !$part) {
       if ($showAllHeaders == 1) {
          $html_header = preg_replace("/\n([\w-]+:)/", "\n<br><b>\$1</b>", links(htmlspecialchars("\n$header")));
       }
       $header_lines = split("\n", $header);
       $working_on = "";
       foreach($header_lines as $line){
           if (substr($line, 0, 8) == "Subject:") {
               $subject = htmlspecialchars(mimeHeaderDecode(substr($line, 9)));
               $working_on = "subject";
           }
           elseif (preg_match("/^From:/i", $line)) {
               $fra = htmlspecialchars(mimeHeaderDecode(substr($line, 6)));
               $working_on = "from";
           }
           elseif (preg_match("/^To:/i", $line)) {
               $til = htmlspecialchars(mimeHeaderDecode(substr($line, 4)));
               $working_on = "to";
           }
           elseif (preg_match("/^CC:/i", $line)) {
               $cc = htmlspecialchars(mimeHeaderDecode(substr($line, 4)));
               $working_on = "cc";
           }
           elseif (preg_match("/^Date:/i", $line)) {
               $dato = htmlspecialchars(substr($line, 6));
               $working_on = "date";
           }
           elseif (preg_match("/^X-Mailer:/i", $line)) {
               $postprogram = htmlspecialchars(substr($line, 10));
               $working_on = "mailer";
           }
           elseif (preg_match("/^message-id/i", $line)) {
               $message_id = substr($line, 12);
               $working_on = "msgid";
           }
           elseif (preg_match("/^Disposition-Notification-To:/i", $line)) {
               $notification_to = htmlspecialchars(substr($line, 29));
               $working_on = "notification";
           }
           elseif (preg_match("/^Status: /i", $line)){
                $msg_status = htmlspecialchars(substr($line, 8, 1));
               $working_on = "status";
           }
           elseif (preg_match("/^Content-Type: /i", $line)) {
               $content_type = htmlspecialchars(substr($line, 14));
               $content_t = substr($line, 14);
               $header_line = "c";
               $working_on = "content-type";
           }
           elseif (preg_match("/^In-Reply-To: /i", $line)) {
               $working_on = "in_reply_to";
           }
           elseif ($working_on == "to") {
               $til .= htmlspecialchars(mimeHeaderDecode($line));
           }
           if ($header_line == "c"){
               if (substr($line, 0, 1) == " ") {
                  $content_type = chop($content_type);
                  $content_t = chop($content_t);
                  $content_type .= " ".htmlspecialchars(substr($line, 1));
                  $content_t .= " ".substr($line, 1);
               }
           }
       }
       $spaces = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
       $html_head_out = "<form action=\"$_SERVER[PHP_SELF]?handling=write\" method=\"post\">\n";
       $html_head_out .= "<table width=\"100%\" border=0 cellspacing=0 cellpadding=2 style=\"background: #d0d0d0\">\n";
       $html_head_out .= "<tr><td class=\"tableheader\" colspan=\"2\">\n";
       $html_head_out .= "<input type=submit class=button name=reply value=\"$s_reply\">\n";
       $html_head_out .= "$spaces\n";
       $html_head_out .= "<input type=submit class=button name=forward value=\"$s_forward\">\n";
       $html_head_out .= "$spaces\n";
       $html_head_out .= "<input type=button class=button value=\"$s_print\" onClick=\"self.print()\">\n";
       $html_head_out .= "$spaces\n";
       if ($showAllHeaders == 1) {
           $html_head_out .= "<a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$showNormalHeaders$link_add\" style=\"text-decoration: none; color: white\">$s_showNormalHeaders</a>\n";
       }
       else {
           $html_head_out .= "<a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$showNormalHeaders&amp;showAllHeaders=1$link_add\" style=\"text-decoration: none; color: white\">$s_showAllHeaders</a>\n";
       }
       $html_head_out .= "$spaces\n";
       $html_head_out .= "<a href=\"$_SERVER[PHP_SELF]?showSource=1&amp;mailNumber=$showNormalHeaders$link_add\" target=\"_blank\" style=\"text-decoration: none; color: white\">$s_showSource</a>\n";
       $html_head_out .= "</td>\n";
       if ($showAllHeaders == 1) {
           $html_head_out .= "<tr><td colspan=2 class=list>\n";
           $html_head_out .= "<p>\n";
           $html_head_out .= $html_header;
           $html_head_out .= "</p>\n";
           $html_head_out .= "</td>\n";
           $html_head_out .= "<td class=list>&nbsp;</td></tr>\n";
       }
       else {
           if (!$subject || $subject == "\r\n") {
               $subject = "&nbsp;";
           }
           if (!$dato || $dato == "\r\n") {
               $dato = "&nbsp;";
           }
           if (!$fra || $fra == "\r\n") {
               $fra = "&nbsp;";
           }
           if (!$til || $til == "\r\n") {
               $til = "&nbsp;";
           }
           if (!$postprogram || $postprogram == "\r\n") {
               $postprogram = "";
           }
           if ($$attachment || $attachment == "\r\n") {
               $attachment = "&nbsp;";
           }
           $html_head_out .= "<tr><td class=list><b>$s_subject:</b></td>\n";
           $html_head_out .= "<td class=list><b>".links($subject)."</b></td>\n";
           $html_head_out .= "<tr><td class=list><b>$s_mDate</b></td>\n";
           $html_head_out .= "<td class=list>".links($dato)."</td>\n";
           $html_head_out .= "<tr><td class=list><b>$s_from:</b></td>\n";
           $html_head_out .= "<td class=list>".links($fra);
    	      $book_addr = preg_replace("/.*&lt;(.*)&gt;.*/i", "\$1", $fra);
              $book_name = preg_replace("/.*&quot;(.*)&quot;.*/i", "\$1", $fra);
              if ($book_name == $fra){
              	if (preg_match("/&lt;.*&gt;/i", $fra)){$book_name = preg_replace("/&lt;.*&gt;.*/i", "", $fra);}
                 if ($book_name == $fra){$book_name = "";}
              }
              if ($debug >= "4"){echo "$book_name\n<br>\n$book_addr\n<br>\n\n\n";}
              $exists = "no";
              if (filesize("userdata/$addressb") != NULL || filesize("userdata/$addressb") != 0){
                  $fd = fopen ("userdata/$addressb", "r");
                  $contents = fread ($fd, filesize ("userdata/$addressb"));
                  fclose ($fd);
                  $entries = explode(";",$contents);
                  for($j = 0; $j < count($entries); $j++) {
                      $entries2 = explode(",",$entries[$j]);
                      if ($debug >= "4"){echo "******<br>\nAddr book:".decrypt($encrypter,$entries2[1])."\n<br>\nAddr mail:$book_addr\n<br>\n-----<br>\n\n";} if (chop(decrypt($encrypter,$entries2[1])) == strtolower($book_addr))
                          $exists = "yes";
                  }
              }
              if ($exists == "no") {
                 $html_head_out .= " &nbsp; &nbsp; <a href=\"$_SERVER[PHP_SELF]?address=true&amp;book_addr=$book_addr&amp;book_name=$book_name\">$s_add_address</a>";
              }
              $html_head_out .= "</td>\n";
           $html_head_out .= "<tr><td class=list><b>$s_mTo:</b></td>\n";
           $html_head_out .= "<td class=list>".links($til)."</td>\n";
           if ($cc != ""){
               $html_head_out .= "<tr><td class=list><b>$s_mcc:</b></td>\n";
               $html_head_out .= "<td class=list>".links($cc)."</td>\n";
           }
           if ($postprogram != ""){
               $html_head_out .= "<tr><td class=list><b>$s_mMailer</b></td>\n";
               $html_head_out .= "<td class=list>".links($postprogram)."</td>\n";
           }
           if ($content_type != ""){
               $html_head_out .= "<tr><td class=list><b>Content-Type:</b></td>\n";
               $html_head_out .= "<td class=list>".$content_type."</td>\n";
               $html_head_out .= "<META http-equiv=Content-Type content=\"$content_type\">\n\n";
           }
           if ($debug >= "2"){                                                                  
               $html_head_out .= "<tr><td class=list><b>Message-Id:</b></td>\n";                
               $html_head_out .= "<td class=list>".htmlspecialchars($message_id)."</td>\n";     
			   }                                                                                    
       }
       $no_multipart = "0";
       if(!$content_type || preg_match("/text\/plain/i", $content_type)){
           $no_multipart = "1";
           if(preg_match("/UTF-8/i", $content_type)){
               $part_body[0] = utf8_decode ( $part_body[0] );
           }

           $html_form_out .= ereg_replace("'", "¥", $part_body[0]);
           $html_form_out = htmlentities ( $html_form_out, ENT_NOQUOTES, "ISO8859-15");
           $part_body[0] = htmlentities ( $part_body[0], ENT_NOQUOTES, "ISO8859-15");
           $html_body = ereg_replace("\n", " <br>\n", $part_body[0]);
           $html_body_out .= quoted_printable_decode(links($html_body));
       }
       elseif(preg_match("/text\/html/i", $content_type)){
           $no_multipart = "1";
           if(!$attach_text){
              $html_head_out .= "<tr><td class=list><b>$s_writeAttach:</b></td>\n";
              $attach_text = "1";
           }
           else{
              $html_head_out .= "<tr><td class=list><b>&nbsp; </b></td>\n";
           }
           $attach_filename = "HTML_Part";
           $att_size = strlen($part_body[0]);
           $att_size = calc_size ($att_size);
           $html_head_out .= "<td class=list><table width=50% border=0 cellspacing=0 cellpadding=2 style=\"background: #d0d0d0\"><tr>";
           $html_head_out .= "<td width=50% class=list><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$showNormalHeaders$link_add&part=1\" target=_blank>";
           $html_head_out .= "<img src=\"images/file.gif\" border=\"0\"> $attach_filename</a></td><td class=list> $att_size</td></table></td></tr>";
       }
       if($debug >= "3"){
           echo "<hr>NON-MULTIPART: \n";                                                    
           echo strlen($html_body_out)." bytes body<br>\n";                                 
           echo "<textarea rows=\"11\" cols=\"70\">".$html_head_out."</textarea>\n\n";      
           echo "<textarea rows=\"11\" cols=\"70\">".$html_body_out."</textarea>\n<br>\n\n";
       }
       for($nr = 0; $nr <= $nr_parts - 1; $nr++){
      	   $nr_link = $nr + 1;
           if($nr == 0){
               if (preg_match ("/^This is a MIME.*message/i", $part_body[$nr])){
                   $part_body[$nr] = "";
               }
               if (preg_match ("/^This message is in MIME format/i", $part_body[$nr])){
                   $part_body[$nr] = "";
               }
               if (preg_match ("/^This is a multi.*part message in MIME format/i", $part_body[$nr])){
                   $part_body[$nr] = "";
               }
           }
           if (preg_match("/quoted-printable/i", $content_transfer[$nr])){
               $part_body[$nr] = quoted_printable_decode($part_body[$nr]);
           }
           if(preg_match("/text\/plain/i", $part_header[$nr]) || ($part_header[$nr] == "" &&
				(!preg_match("/base64/", $content_transfer[$nr]) || !preg_match("/UTF-8/", $content_transfer[$nr]) ))){
               if(preg_match("/ base64/", $content_transfer[$nr])){
                   $part_body[$nr] = base64_decode($part_body[$nr]);
               }
               if(preg_match("/UTF-8/i", $content_type)){
                   $part_body[$nr] = utf8_decode($part_body[$nr]);
               }
               if (!preg_match ("/^This is a MIME.*message/i", $part_body[$nr]) && $no_multipart == "0"){
                   $html_form_out .= ereg_replace("'", "¥", $part_body[$nr]);
                   $html_form_out = htmlentities ( $html_form_out, ENT_NOQUOTES, "ISO8859-15");
                   $part_body[$nr] = htmlentities ( $part_body[$nr], ENT_NOQUOTES, "ISO8859-15");
                   $html_body = ereg_replace("\n", " <br>\n", $part_body[$nr]);
                   $html_body_out .= links($html_body);
               }

           }
           elseif(preg_match("/text\/html/i", $part_header[$nr])){
               if(!$attach_text){
                  $html_head_out .= "<tr><td class=list><b>$s_writeAttach:</b></td>\n";
                  $attach_text = "1";
               }
               else{
                  $html_head_out .= "<tr><td class=list><b>&nbsp; </b></td>\n";
               }
               $attach_filename = "HTML_Part_$nr";
               $att_size = strlen($part_body[$nr]);
               $att_size = calc_size ($att_size);
               $html_head_out .= "<td class=list><table width=50% border=0 cellspacing=0 cellpadding=2 style=\"background: #d0d0d0\"><tr>";
               $html_head_out .= "<td class=list width=50%><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$showNormalHeaders$link_add&part=$nr_link\" target=_blank>";
               $html_head_out .= "<img src=\"images/file.gif\" border=\"0\"> $attach_filename</a></td><td class=list> $att_size</td></table></td></tr>";
           }
           elseif(preg_match("/: message\/.*/i", $part_header[$nr])){
               if(!$attach_text){
                  $html_head_out .= "<tr><td class=list><b>$s_writeAttach:</b></td>\n";
                  $attach_text = "1";
               }
               else{
                  $html_head_out .= "<tr><td class=list><b>&nbsp; </b></td>\n";
               }
               $attach_filename = "Mail_Notification_$nr";
               $att_size = strlen($part_body[$nr]);
               $att_size = calc_size ($att_size);
               $html_head_out .= "<td class=list><table width=50% border=0 cellspacing=0 cellpadding=2 style=\"background: #d0d0d0\"><tr>";
               $html_head_out .= "<td class=list width=50%><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$showNormalHeaders$link_add&part=$nr_link\" target=_blank>";
               $html_head_out .= "<img src=\"images/file.gif\" border=\"0\"> $attach_filename</a></td><td class=list> $att_size</td></table></td></tr>";
           }
           elseif(preg_match("/: [message,text]+\/rfc822.*/i", $part_header[$nr])){
               if(!$attach_text){
                  $html_head_out .= "<tr><td class=list><b>$s_writeAttach:</b></td>\n";
                  $attach_text = "1";
               }
               else{
                  $html_head_out .= "<tr><td class=list><b>&nbsp; </b></td>\n";
               }
               $attach_filename = "Attached_Mail_$nr";
               $att_size = strlen($part_body[$nr]);
               $att_size = calc_size ($att_size);
               $html_head_out .= "<td class=list><table width=50% border=0 cellspacing=0 cellpadding=2 style=\"background: #d0d0d0\"><tr>";
               $html_head_out .= "<td class=list width=50%><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$showNormalHeaders$link_add&attmail=$nr_link\" target=_blank>";
               $html_head_out .= "<img src=\"images/file.gif\" border=\"0\"> $attach_filename</a></td><td class=list> $att_size</td></table></td></tr>";
           }
           else{
              if (preg_match("/Content-/i", $part_header[$nr])){
                 if(preg_match("/filename=/i", $part_header[$nr])){
                     $attach_filename = ereg_replace("\n", " ",  $part_header[$nr]);
                     $attach_filename = preg_replace("/^.*filename=\"(.*)\".*$/", "\$1",  $attach_filename);
                     $attach_filename = mimeHeaderDecode($attach_filename);
                 }
                 elseif(preg_match("/name=/i", $part_header[$nr])){
                     $attach_filename = ereg_replace("\n", " ",  $part_header[$nr]);
                     $attach_filename = preg_replace("/^.*name=\"(.*)\".*$/", "\$1",  $attach_filename);
                     $attach_filename = mimeHeaderDecode($attach_filename);
                 }
                 else{
                     $attach_filename = "$s_Attach_unknown";
                 }
                 $file_ext = strtolower(substr($attach_filename, -3));
                 if (ereg($file_ext, $ext_arry)){
                    $target = "target=_blank";
                 }
                 if(!$attach_text){
                    $html_head_out .= "<tr><td class=list><b>$s_writeAttach:</b></td>\n";
                    $attach_text = "1";
                 }
                 else{
                    $html_head_out .= "<tr><td class=list><b>&nbsp; </b></td>\n";
                 }
                 if(preg_match("/base64/i", $content_transfer[$nr])){
                     $part_body[$nr] = base64_decode($part_body[$nr]);
                 }
               $att_size = strlen($part_body[$nr]);
               $att_size = calc_size ($att_size);
               $html_head_out .= "<td class=list><table width=50% border=0 cellspacing=0 cellpadding=2 style=\"background: #d0d0d0\"><tr>";
               $html_head_out .= "<td class=list width=50%><a href=\"$_SERVER[PHP_SELF]?showNormalHeaders=$showNormalHeaders$link_add&part=$nr_link\" $target>";
               $html_head_out .= "<img src=\"images/file.gif\" border=\"0\"> $attach_filename</a></td><td class=list> $att_size</td></table></td></tr>";
               }
           }
       }
       if($debug >= "3"){
           echo "MULTIPART: \n";                                                               
           echo strlen($html_body_out)." bytes body<br>\n";                                   
           echo "<textarea rows=\"11\" cols=\"70\">".$html_head_out."</textarea>\n\n";        
           echo "<textarea rows=\"11\" cols=\"70\">".$html_body_out."</textarea>\n<br>\n\n";  
       }
       $html_head_out .= "</table>\n";
       if($GLOBALS[flow_contr] == "1"){
           $time_now = time();
           $time_stamp = $time_now - $GLOBALS[flow_time];
           $time_stamp = substr("000$time_stamp", -3);
           $flow_text = "$time_stamp >>> index.php -> prog_mail_display.php -> interpret message nr. $mailNumber -> STOP \n";
           fwrite($GLOBALS[flow_error_debug], $flow_text);
       }
       begin_page(2,"",$stylesheet,2);
       echo "$html_head_out";
       echo "<br>\n$html_body_out";
       echo "<input type=hidden name=ebrev value='$html_form_out'>\n";
       echo "<input type=hidden name=sendTo value=\"$fra\">\n";
       echo "<input type=hidden name=title value=\"$subject\">\n";
       echo "</form>\n";
       if($notification_to && $msg_status == "U"){
           echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";
           echo "<!--\n";
           echo "  Fenster = window.open('$_SERVER[PHP_SELF]?handling=write&notification=$notification_to&notif_subject=$subject','write','resizable=yes,scrollbars=no,width=380,height=200');\n";
           echo "  Fenster.moveTo((screen.width-340)/2,screen.height/2);\n";
           echo "  Fenster.focus();\n";
           echo "//-->\n";
           echo "</script>\n\n";
       }
       end_page();
    }
    unset($header);
    unset($body);
}
?>