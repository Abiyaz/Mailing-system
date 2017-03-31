<?php

if (!function_exists('read_vmbox_file')){
function read_vmbox_file($filename) {
    if($GLOBALS["debug"] >= 3){ echo "Filename=$filename<br>\n"; }
    if (file_exists($filename)) {
    $flow_contr = $GLOBALS["flow_contr"];
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> Read vmbox -> Function start (1) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
        $vmbox_lines = file($filename);
        foreach ($vmbox_lines as $line) {
            $line = chop($line);
            $vmbox_content .= "$line\n";
        }
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> Read vmbox -> After loading file (2) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
        unset($vmbox_lines);
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> Read vmbox -> After freeing line content variable (3) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
        if($GLOBALS[flow_contr] == "1"){
            $time_now = time();
            $time_stamp = $time_now - $GLOBALS[flow_time];
            $time_stamp = substr("000$time_stamp", -3);
            $flow_text = "$time_stamp >>> index.php -> prog_mail_file.php -> reading vmbox file -> getting file content -> STOP \n";
            fwrite($GLOBALS[flow_error_debug], $flow_text);
        }
        $time_now = time();
        $time_diff = $time_now - $GLOBALS[flow_time];
        if ($time_diff >= 25){
        }
        $mails = preg_split ("/\n\nFrom .*\n/", $vmbox_content);
        unset($vmbox_content);
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> Read vmbox -> After splitting content to mails (4) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
        $mail0 = split("\n", $mails[0]);
        $trash = array_shift($mail0);
        $mails[0] = "\n".join("\n", $mail0);
        unset($mail0);
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> Read vmbox -> After selecting mail and freeing variables (5) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
        return $mails;
    }
    if($GLOBALS[flow_contr] == "1"){
        $time_now = time();
        $time_stamp = $time_now - $GLOBALS[flow_time];
        $time_stamp = substr("000$time_stamp", -3);
        $flow_text = "$time_stamp >>> index.php -> prog_mail_file.php -> reading vmbox file -> splitting file content -> STOP \n";
        fwrite($GLOBALS[flow_error_debug], $flow_text);
    }
}

function write_vmbox_file($filename, $mails, $mode = "w") {
    $file_content = "";
    $vmbox_header = array();
    $vmbox_date = array();
    $vmbox_mail = array();
    $counter = 0;
    foreach ($mails as $mail) {
        if ($mail != ""){
            list($split_temp) = preg_split ("/\n\n/", $mail);
            $mail_lines = split("\n", $split_temp);
            $ReturnPath = "";
            $Date = "";
            $found = "0";
            $line_nr = "0";
            foreach ($mail_lines as $line){
                $line_nr = $line_nr + 1;
                if(preg_match("/^Return-Path: /", $line) && $ReturnPath == ""){
                    list($trash, $ReturnPath) = split(" ", $line);
                    $ReturnPath = preg_replace("/<(.*)>/", "\$1", $ReturnPath);
                }
                if(preg_match("/^Date: /", $line)){
                    list($trash, $date_string) = split(": ", $line);
                    $date = strtotime ("$date_string");
                    $sort_date = date("YmdHis", $date);
                    $Date = date("r", $date);
                    $sort_date = $sort_date.$counter;
                    if($GLOBALS[flow_contr] == "1"){
                        $time_now = time();
                        $time_stamp = $time_now - $GLOBALS[flow_time];
                        $time_stamp = substr("000$time_stamp", -3);
                        $flow_text = "$time_stamp >>> index.php -> prog_mail_file.php -> Analyse MailNr. $counter stopped at line $line_nr \n";
                        fwrite($GLOBALS[flow_error_debug], $flow_text);
                    }
                    break;
                }
            }
            if($GLOBALS[flow_contr] == "1"){
                $time_now = time();
                $time_stamp = $time_now - $GLOBALS[flow_time];
                $time_stamp = substr("000$time_stamp", -3);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_file.php -> Analyse MailNr. $counter stopped at line $line_nr \n     -> ReturnPath: $ReturnPath \n      -> Date: $Date \n";
            fwrite($GLOBALS[flow_error_debug], $flow_text);
            }
            $vmbox_header[$counter] = "From $ReturnPath $Date";
            $vmbox_date[$sort_date] = $counter;
            $vmbox_mail[$counter] = $mail;
            $counter = $counter +1;
        }
    }
    ksort ($vmbox_date);
    while (list ($key, $val) = each ($vmbox_date)) {
        if (!preg_match("/\nReturn-Path: /", $vmbox_mail[$val])){
            $vmbox_header[$val] = $vmbox_header[$val]."\n";
        }
        $temp_mail = $vmbox_header[$val].$vmbox_mail[$val]."\n\n";
        $file_content .= $temp_mail;
    }
    if ($file_content != "") {
        if (!$handle = fopen($filename, "$mode")) {
            echo "Error: file open.";
            exit;
        }
        if (!fwrite($handle, $file_content)) {
            echo "Error: file write.";
            exit;
        }
        fclose($handle);
    }
    else{
        unlink($filename);
    }
}
}
if ($type == "compl" && $mailNumber) {
    if($GLOBALS["debug"] >= 3){ echo "type=$type; mailNumber=$mailNumber; actual_mail_nr=$actual_mail_nr<br>\n"; }     ###debugg
    $mailNumber = $mailNumber -1;
    if($mailNumber != $actual_mail_nr || $actual_mail_nr == ""){
        if($flow_contr == "1"){
            $time_now = time();
            $time_stamp = $time_now - $flow_time;
            $time_stamp = substr("000$time_stamp", -3);
            $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> get complete mail for $mode  Nr: $mailNumber -> START\n";
            fwrite($flow_error_debug, $flow_text);
        }
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> Start of Mail request (1) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
        $filename = "userdata/$ind_config"."_$mode.vmbox";
        $mails = read_vmbox_file($filename);
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> Mail content received from function (2) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
        if($debug >= "3"){
            echo "Mail nr.: $mailNumber<br><textarea rows=\"11\" cols=\"150\">".$mails[$mailNumber]."</textarea><br>\n";
        }
        $mail_compl = $mails[$mailNumber];
	if($flow_contr == "1"){
	    $time_now = time();
	    $time_stamp = $time_now - $flow_time;
	    $time_stamp = substr("000$time_stamp", -3);
	    $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> Befor returning mail content (3) -> MemoryUsage: " . memory_get_usage() . " Bytes\n";
	    fwrite($flow_error_debug, $flow_text);
	}
        if(preg_match("/Status: U/i",$mails[$mailNumber])){
            if($flow_contr == "1"){
                $time_now = time();
                $time_stamp = $time_now - $flow_time;
                $time_stamp = substr("000$time_stamp", -3);
                $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> change status, mail for $mode  Nr: $mailNumber -> START\n";
                fwrite($flow_error_debug, $flow_text);
            }
            $mails[$mailNumber] = preg_replace("/Status: U/", "Status: RO", $mails[$mailNumber]);
            write_vmbox_file($filename, $mails);
        }
        if($flow_contr == "1"){
            $time_now = time();
            $time_stamp = $time_now - $flow_time;
            $time_stamp = substr("000$time_stamp", -3);
            $flow_text = "$time_stamp >>> index.php -> prog_mail_list.php -> prog_mail_file.php -> get complete mail for $mode Nr: $mailNumber -> STOP\n";
            fwrite($flow_error_debug, $flow_text);
        }
        $actual_mail_nr = $mailNumber;
    }
    else{
    }
}
if ($type == "stat") {
    $filename = "userdata/$ind_config"."_$mode.vmbox";
    $mails = read_vmbox_file($filename);
    $antalEbreve = count($mails);
}
if ($type == "head" && $mailNumber) {
    $mailNumber = $mailNumber -1;
    if(!$mails){
        $filename = "userdata/$ind_config"."_$mode.vmbox";
        $mails = read_vmbox_file($filename);
    }
    $split_temp = preg_split ("/\n\n/", $mails[$mailNumber]);
    $header = $split_temp[0];
}
if ($type == "size" && $mailNumber) {
    $mailNumber = $mailNumber -1;
    if(!$mails){
        $filename = "userdata/$ind_config"."_$mode.vmbox";
        $mails = read_vmbox_file($filename);
    }
    $stoerrelse = strlen($mails[$mailNumber]);
    $stoerrelse = calc_size($stoerrelse);
}
if ($type == "del" && $deleteMessage) {
    $filename = "userdata/$ind_config"."_$mode.vmbox";
    $mails = read_vmbox_file($filename);
    foreach($deleteMessage as $del){
        $del = $del - 1;
        $mails[$del] = "";
    }
    write_vmbox_file($filename, $mails);
}
if ($type == "add" && $deleteMessage) {
    $to_folder = chop($to_folder);
    $filename = "userdata/$ind_config"."_$to_folder.vmbox";
    write_vmbox_file($filename, $mail_copy, "a");
}
if ($type == "sort") {
    $mails = array();
    $to_folder = chop($to_folder);
    $filename = "userdata/$ind_config"."_$to_folder.vmbox";
    $mails = read_vmbox_file($filename);
    write_vmbox_file($filename, $mails);
}
if ($type == "folder_info") {
    if(!$mode){ $folder_split = split("\n", $folder); }
    else{ $folder_split[0] = $mode; }
    begin_page(0, "",$stylesheet,1,"bottom");
    echo "<center><table><tr><td class=list><a href=\"javascript:window.close()\"><b>$s_close_window</b></a></table></center>\n";
    echo "<table width=100%>\n";
    echo "<tr><td colspan=3>&nbsp</td></tr>\n";
    foreach($folder_split as $foldername){
        $foldername = chop($foldername);
        if ($foldername == ""){ continue; }
        $filename = "userdata/$ind_config"."_$foldername.vmbox";
        $stat = stat($filename);
        $size = calc_size($stat[7]);
        $stat[7] = number_format($stat[7], 0, ',', '.');
        if(!$mode){
            $link = "<tr><td colspan=3 class=list align=center><a href=\"$_SERVER[PHP_SELF]?frame=folder_info&details=$foldername\"><b>$s_folder_details</b></a></td></tr>\n";
        }
        echo "<tr><td colspan=3 class=tableheader align=center><b>$foldername</b></td></trt>\n";
        echo "<tr><td colspan=3 class=list>&nbsp;$filename</td></trt>\n";
        echo "<tr><td class=list>&nbsp;$s_folder_size <td class=list align=right>$size <td class=list align=right>".$stat[7]." $s_bytes</td></tr>\n";
        $date_access = ereg_replace(" ", "&nbsp;",date("r",$stat[8]));
        $date_modification = ereg_replace(" ", "&nbsp;",date("r",$stat[9]));
        $date_change = ereg_replace(" ", "&nbsp;",date("r",$stat[10]));
        echo "<tr><td class=list>&nbsp;$s_last_access <td class=list colspan=2>$date_access</td></tr>\n";
        echo "<tr><td class=list>&nbsp;$s_last_modif <td class=list colspan=2>$date_modification</td></tr>\n";
        echo "<tr><td class=list>&nbsp;$s_last_change <td class=list colspan=2>$date_change</td></tr>\n";
        echo "$link";
        echo "<tr><td colspan=3>&nbsp</td></tr>\n";
        if($mode){
            $type = "stat";
            include("prog_mail_file.php");
            echo "<tr><td class=list>$s_mails_total <td class=list colspan=2>$antalEbreve</td></tr>\n";
            $type = "head";
            $unread = 0;
            for($i = 1; $i <= $antalEbreve; $i++) {
                $mailNumber = $i;
                include("prog_mail_file.php");
                if(preg_match("/Status: U/i",$header)){
                    $unread++;
                }
            }
            echo "<tr><td class=list>$s_mails_unread <td class=list colspan=2>$unread</td></tr>\n";
            echo "<tr><td colspan=3>&nbsp</td></tr>\n";
            $all_folder = "<tr><td class=list align=center><a href=\"$_SERVER[PHP_SELF]?frame=folder_info\"><b>$s_all_folder</b></a>\n";
        }
    }
    echo "</table>\n";
    echo "<center><table>\n$all_folder<tr><td class=list><a href=\"javascript:self.close()\"><b>$s_close_window</b></a></table></center>\n";
    end_page();
}
?>
