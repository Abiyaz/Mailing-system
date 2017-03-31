<?php

if ($type == "compl" && $mailNumber) {
    $mail_compl = "";
    fputs($fp, "RETR $mailNumber\r\n");
    $foersteLinje = fgets($fp, 1024);
    while (substr($line = fgets($fp, 1024), 0, 3) != ".".chr(13).chr(10)) {
        $mail_compl .= chop($line)."\n";
    }
    $actual_mail_nr = $mailNumber;
}

if ($type == "stat") {
    fputs($fp, "STAT\r\n");
    $ebreve = fgets($fp, 1024);
    $ebreve = split(" ", $ebreve);
    $antalEbreve = $ebreve[1];
}
if ($type == "head" && $mailNumber) {
    $header = "";
    fputs($fp, "TOP $mailNumber 0\r\n");
    while (substr($line = fgets($fp, 1024), 0, 2) != chr(13).chr(10)) {
        $header .= chop($line)."\n";
    }
    while (substr($line, 0, 1) != ".") {
        $line = fgets($fp, 1024);
    }
}
if ($type == "size" && $mailNumber) {
    fputs($fp, "LIST $mailNumber\r\n");
    $answer_size = fgets($fp, 1024);
    $msg_size = split(" ", $answer_size);
    if ($msg_size[0] == "+OK"){
        $stoerrelse = $msg_size[2];
        $stoerrelse = calc_size ($stoerrelse);
    }
}
if ($type == "del" && $deleteMessage) {
    for ($i = 0; $i < sizeof($deleteMessage); $i++) {
        fputs($fp, "DELE " . $deleteMessage[$i] . "\r\n");
        $foersteLinje = fgets($fp, 1024);
    }
    fputs($fp, "QUIT\r\n");
    fclose($fp);
}
?>
