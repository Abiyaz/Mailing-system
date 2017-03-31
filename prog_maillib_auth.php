<?php
$smtp_port=25;
$debug=0;

function setPriority($priority){
    $priorities = array('1 (Highest)', '2 (High)', '3 (Normal)', '4 (Low)', '5 (Lowest)');
    if(!intval($priority)){
        error($GLOBALS["s_sentNoPriority"],1,1,$GLOBALS["stylesheet"]);
    }
    if(!isset( $priorities[$priority-1])){
        error($GLOBALS["s_sentNoPriority"],1,1,$GLOBALS["stylesheet"]);
    }
    return "X-Priority: ".$priorities[$priority-1]."\n";
}

function setFrom($from){
    if (!$from){
        error($GLOBALS["s_sentNoFrom"],1,1,$GLOBALS["stylesheet"]);
    }
    else {
        $fromText = stripslashes($from);
        if(!is_string($fromText)){
            error($GLOBALS["s_sentWrongFrom"],1,1,$GLOBALS["stylesheet"]);
        }
    }
    return "From: $fromText\n";
}

function checkSMTPAnswer($answer, $socket){
    if(substr($answer,0,3) >= 500){
        fputs ($socket, "QUIT\r\n");
        $smtp_answer = fgets ($socket, 1024);
        fclose($socket);
        error($GLOBALS["s_emailNotSent"] . " " . $answer,1,1,$GLOBALS["stylesheet"]);
    }
}

if (!$toText) {
    error($s_noRecipient,1,1,$GLOBALS["stylesheet"]);
}

if (!eregi("[_.a-z0-9]+@[a-z0-9]+", $toText)) {
    error($s_invalidRecipient,1,1,$GLOBALS["stylesheet"]);
}

if (!$smtp_server) {
    error($GLOBALS["s_noSMTP"],1,1,$GLOBALS["stylesheet"]);
}

$toText = stripslashes($toText);
$subjectText = stripslashes($subjectText);
$msgText = stripslashes($msgText);

$positie = strpos($msgText,$message);
$part1 = substr($msgText,0,$positie);
$part2 = substr($msgText,$positie);

$msgText = $part1;
$msgText .= $part2;
$msgText .= "\n\n".$footer."";

$headers  = setFrom($fromText);

$headers .= "To: $toText\n";
if ($ccText) {
    $headers .= "CC: $ccText\n";
}

$headers .= "Subject: $subjectText\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= setPriority($priority);
if($read_notification) {
    $headers .= "Disposition-Notification-To: $fromText\n";
}
$headers .= "X-Mailer: PHP/POPMail $version\n";
$headers .= "Date: " . date('r') . "\n";

if ($File_att) {
    $fil_type = "application/unknown";
    $boundary = uniqid("POPMail");
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
    $msgText = "--$boundary\nContent-Type: text/plain; charset=\"UTF-8\"\nContent-Transfer-Encoding: 8bit\n\n" . utf8_encode($msgText) . "\n\n";
    foreach($File_name as $key => $value){
        $filindhold = chunk_split(base64_encode($Files_attached[$File_name[$key]]));
        $disposition = "attachment";
        $encoding = "base64";
        $fil_name = $File_name[$key];
        $msgText .= "--$boundary\nContent-Type: $fil_type; name=\"$fil_name\"\nContent-Transfer-Encoding: $encoding\nContent-Disposition: $disposition; filename=\"$fil_name\"\n\n$filindhold\n";
    }
    $msgText .= "\n--$boundary--";
    $files_temp = "";
    $_SESSION["Files_attached"] = $files_temp;
}
else {
    $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
    $msgText = utf8_encode($msgText);
}

if ($save_message){
    $mail_copy[0]  = "Return-Path: <$fromText>\n";
    $mail_copy[0] .= "Date: ".date("r",time())."\n";
    $mail_copy[0] .= $headers;
    $mail_copy[0] .= "Status: RO\n";
    $msgText_save = preg_replace("/\nFrom /", "\n>From ", $msgText);
    $mail_copy[0] .= "\n$msgText_save\n";
    $type = "add";
    $deleteMessage = "1";
    include("prog_mail_file.php");
}

if (strstr($fromText, "<") && strstr($fromText, ">")){
    list($name, $email) = split("<", $fromText);
    $smtpFrom = substr($email, 0, -1);
}
else $smtpFrom = $fromText;

$MessageText  = "$headers\r\n";
$msgLines = preg_split("/[\r]\n/",$msgText);
$a=0;
while($msgLines[$a]){
    $msgLines[$a] = chop($msgLines[$a]);
    if($msgLines[$a] == "."){
        $msgLines[$a] = "..";
    }
    $a++;
}
$msgText = join("\r\n",$msgLines);
$MessageText .= "$msgText\r\n";

$MessageText = preg_replace("/\r/", "", $MessageText);
$MessageText = preg_replace("/\n\.\n/", "\n..\n", $MessageText);
$MessageText = preg_replace("/\n/", "\r\n", $MessageText);
if($debug > 0){echo "<b>Debug Results:</b><br>"; echo "Connecting to SMTP mail server " . $smtp_server . ":" . $smtp_port . ".<br>";}
$fp = fsockopen($smtp_server,$smtp_port) or error($GLOBALS["s_emailNotSent"] . "<br>" . $GLOBALS["s_smtpNoConnection"],1,1,$GLOBALS["stylesheet"]);
$smtp_answer=fgets($fp, 1024);
if (!$fp) return false;

if ($fp && $debug > 0) {echo "Connected.<br>"; echo "$smtp_answer<br>";}

fputs($fp, "EHLO {$_SERVER['SERVER_NAME']}\r\n");
$smtp_answer = fgets($fp, 1024);
if($debug > 0){echo "EHLO {$_SERVER['SERVER_NAME']}<br>"; echo "$smtp_answer<br>";}
checkSMTPAnswer($smtp_answer, $fp);

fputs($fp, "AUTH LOGIN\r\n");
if($debug > 0){echo "AUTH LOGIN<br>";}

$ct = 1;
while ($ct <= 20) {
    $smtp_answer = fgets($fp, 1024);
    if($debug > 0){echo "$smtp_answer<br>";}
    if(substr($smtp_answer, 3, 1) == " ") {
        $ct = 0;
        break;
    }
    $ct++;
}

fputs($fp, base64_encode($user)."\r\n");
$smtp_answer = fgets($fp, 1024);
if($debug > 0){echo "Username = ".base64_encode($user)."<br>"; echo "$smtp_answer<br>";}

fputs($fp, base64_encode($accesscode)."\r\n");
$smtp_answer = fgets($fp, 1024);
if($debug > 0){echo "Password = ".base64_encode($accesscode)."<br>"; echo "$smtp_answer<br>";}
checkSMTPAnswer($smtp_answer, $fp);

fputs($fp, "RSET\r\n");
$smtp_answer = fgets($fp, 1024);
if($debug > 0){echo "RSET<br>"; echo "$smtp_answer<br>";}
checkSMTPAnswer($smtp_answer, $fp);

$smtpFrom = preg_replace("/.*[ ^\<\"]([a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,6}).*/", "\$1", $smtpFrom);
fputs($fp, "MAIL FROM: $smtpFrom\r\n");
$smtp_answer = fgets($fp, 1024);
if($debug > 0){echo "MAIL FROM: &lt;" . $smtpFrom . "&gt;<br>"; echo "$smtp_answer<br>";}
checkSMTPAnswer($smtp_answer, $fp);

$rcpts = $toText.",".$ccText.",".$bccText;
$rcpts_temp = split(",",$rcpts);
$add = "";
foreach($rcpts_temp as $rcpt){
    $rcptEnvelop = preg_replace("/.*[ ^\<\"]([a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,6}).*/", "\$1", $rcpt);

    if($deliver_notification) {
        $add = "  NOTIFY=SUCCESS";
    }

    if($rcptEnvelop){
        fputs($fp, "RCPT TO: <$rcptEnvelop>$add\r\n");
        $smtp_answer = fgets($fp, 1024);
        if($debug > 0){echo "RCPT TO: &lt;" . $rcptEnvelop . "&gt;$add<br>"; echo "$smtp_answer<br>";}
        checkSMTPAnswer($smtp_answer, $fp);
    }
}
fputs($fp, "DATA\r\n");
$smtp_answer = fgets($fp, 1024);
if($debug > 0){echo "DATA<br>"; echo "$smtp_answer<br>";}
checkSMTPAnswer($smtp_answer, $fp);

fputs($fp, "$MessageText\r\n.\r\n");
$smtp_answer = fgets($fp, 1024);
if($debug > 0){echo preg_replace("/[\r]*\n/", "<br>\n", $MessageText) . "<br>$smtp_answer<br>";}
checkSMTPAnswer($smtp_answer, $fp);

if (!strcasecmp(substr($smtp_answer, 0, 3), "250")) {
    echo $GLOBALS["s_emailSent"] . "<br>\n";
    echo "$toText";
    if ($ccText)
        echo ", $ccText";
    if ($bccText)
        echo ", $bccText";
}
else error($GLOBALS["s_emailNotSent"], 1, 1, $GLOBALS["stylesheet"]);

fputs ($fp, "QUIT\r\n");
$smtp_answer = fgets ($fp, 1024);
fclose($fp);
if($debug > 0){echo "<br>QUIT<br>"; echo "$smtp_answer<br>"; echo "Disconnected.<br>";}
if($debug > 0){echo "<b>End of DEBUG!</b><br><br>";}
?>