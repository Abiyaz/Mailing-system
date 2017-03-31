<?php
function links($tekst) {
    $tekst = str_replace("=E6", "æ", $tekst);
    $tekst = str_replace("=F8", "ø", $tekst);
    $tekst = str_replace("=E5", "å", $tekst);
    $tekst = str_replace("=C6", "Æ", $tekst);
    $tekst = str_replace("=D8", "Ø", $tekst);
    $tekst = str_replace("=C5", "Å", $tekst);
    $tekst = str_replace("=A7", "§", $tekst);
    $tekst = str_replace("=A4", "¤", $tekst);
    $tekst = str_replace("=3D", "=", $tekst);
    $tekst = str_replace("=20", " ", $tekst);
    $tekst = str_replace("=A3", "£", $tekst);
    $tekst = str_replace("=E4", "ä", $tekst);
    $tekst = str_replace("=F6", "ö", $tekst);
    $tekst = str_replace("=C4", "Ä", $tekst);
    $tekst = str_replace("=D6", "Ö", $tekst);
    $tekst = str_replace("=E9", "é", $tekst);
    $tekst = str_replace("<", "&lt;", $tekst);
    $tekst = str_replace(">", "&gt;", $tekst);
    $tekst = str_replace("&lt;br&gt;", "<br>", $tekst);
    $tekst = eregi_replace("([_.a-z0-9-]+@[_.a-z0-9-]+\.[a-z]{2,7})", "<a href=\"$GLOBALS[PHP_SELF]?handling=write&amp;sendTo=\\1\">\\1</a>", $tekst);
    $tekst = eregi_replace("((http://|https://|ftp://|news://|file://)[_.a-z0-9-]+[a-z]{2,3}[a-z0-9/_:@=.+?#%&~-]*[^. |! |? |, |. |.\r|\) |\).])", "<a href=\"\\1\" target=\"blank\">\\1</a>", $tekst);
    return $tekst;
}
function begin_page($compose = 0, $titel = "", $stylesheet = "standard", $visBody = 1, $layout = "") {
    if ($titel) {
        $titel = "POPMail ".$GLOBALS["version"]." | $titel";
    }
    else {
        $titel = "POPMail ".$GLOBALS["version"];
    }

    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
    echo "<html>\n<head>\n<META http-equiv=Content-Type content=\"text/html; charset=iso-8859-15\">\n";
    echo "<title>$titel</title>\n";
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style/$stylesheet.css\">\n";

    if ($compose == 1) {
?>
<SCRIPT type="text/javascript">
function addTo(toadd, list){
 if (list.value > " ") {
  list.value=list.value + ", " + toadd.options[toadd.selectedIndex].value;
 } else {
  list.value=toadd.options[toadd.selectedIndex].value;
 }
}
</SCRIPT>
<?php
    }
    if ($compose == 2){
       echo "<script type=\"text/javascript\">\n";
       echo "  function refresh()\n";
       echo "  {\n";
       echo "    parent.inbox.location.reload()\n";
       echo "  }\n";
       echo "</script>\n";
    }
    echo "</head>\n\n";

    if ($visBody == 1) {
        if ($layout) {
            $layout = "class=\"$layout\"";
        }
        echo "<body $layout>\n";
    }
    if ($visBody == 2) {
        if ($layout) {
            $layout = "class=\"$layout\"";
        }
        echo "<body $layout onLoad=\"refresh()\">\n";
    }
}
function end_page($end = 0, $event = "") {
    if ($end == "1"){
echo "
<script LANGUAGE=\"JavaScript\" type=\"text/javascript\">
<!--
// Javascript Countdown
var delay = $event;
var now = new Date();
var seconds = (event - now) / 1000;
var minutes = seconds / 60;
var hours = minutes / 60;
var days = hours / 24;
var out = \"--:--:--\";
var event = (new Date()).getTime() + (delay * 1000);
ID=window.setTimeout(\"update();\", 1000);
function update() {
now = new Date();
time = (event - now); //;
days = (time - (time % 86400000)) / 86400000;
time = time - (days * 86400000);
hours = (time - (time % 3600000)) / 3600000;
time = time - (hours * 3600000);
minutes = (time - (time % 60000)) / 60000;
time = time - (minutes * 60000);
seconds = (time - (time % 1000)) / 1000;
out = \"\";
if(hours < 10) out = out+\"0\";
out = out+hours+\":\";
if(minutes < 10) out = out+\"0\";
out = out+minutes+\":\";
if(seconds < 10) out = out+\"0\";
out = out+seconds;
if(time < 0) out = \"00:00:00\";
document.countdown.time.value = out;
ID=window.setTimeout(\"update();\",1000);
}
// -->
</script>

<script LANGUAGE=\"JavaScript\" type=\"text/javascript\">
<!--
/* Javascript Time & Date */
function show(){
var Digital=new Date()
var day=Digital.getDate()
var month=Digital.getMonth()
month=month+1
var year=Digital.getFullYear()
var hours=Digital.getHours()
var minutes=Digital.getMinutes()
var seconds=Digital.getSeconds()
if (hours==0)
hours=12

if (day<=9)
day=\"0\"+day
if (month<=9)
month=\"0\"+month
year=year-(Math.round((year-50)/100)*100)
if (year<=9)
year=\"0\"+year

if (hours<=9)
hours=\"0\"+hours
if (minutes<=9)
minutes=\"0\"+minutes
if (seconds<=9)
seconds=\"0\"+seconds
document.clock.date.value=day+\".\"+month+\".\"+year
document.clock.time.value=hours+\":\"+minutes+\":\"+seconds
setTimeout(\"show()\",1000)
}
show()
//-->
</script>

";
    }
    echo "</body>\n</html>\n";
}
function error($message, $head = 0, $foot = 0,$stylesheet = "standard") {
    if ($head == 1) {
        begin_page(0,"",$stylesheet);
    }

    echo "<h3>$GLOBALS[s_error]</h3>\n";
    echo "<p>$message</p>\n";
    echo "<form action=\"\">\n<input type=button class=button value=\"&lt;&lt;&lt; $GLOBALS[s_back]\" onClick=\"history.back()\">\n</form>\n";

    if ($foot == 1) {
        end_page();
    }

    exit;
}
function encrypt($password,$strtoencrypt) {

    $strtoencrypt = str_replace("\t","[tab]",$strtoencrypt);
    $strtoencrypt = str_replace("\n","[new]",$strtoencrypt);
    $strtoencrypt = str_replace("\r","[ret]",$strtoencrypt);

    global $ralphabet;
    global $alphabet;

    for( $i = 0; $i < strlen($password); $i++ ) {
        $cur_pswd_ltr = substr($password,$i,1);
        $pos_alpha_ary[] = substr(strstr($alphabet,$cur_pswd_ltr),0,strlen($ralphabet));
    }

    $i = 0;
    $n = 0;
    $nn = strlen($password);
    $c = strlen($strtoencrypt);

    while($i < $c) {
        $encrypted_string .= substr($pos_alpha_ary[$n],strpos($ralphabet,substr($strtoencrypt,$i,1)),1);
        $n++;
        if($n == $nn) $n = 0;
        $i++;
    }

    return $encrypted_string;
}
function decrypt($password,$strtodecrypt) {

    global $ralphabet;
    global $alphabet;

    for($i = 0; $i < strlen($password); $i++) {
        $cur_pswd_ltr = substr($password,$i,1);
        $pos_alpha_ary[] = substr(strstr($alphabet,$cur_pswd_ltr),0,strlen($ralphabet));
    }

    $i = 0;
    $n = 0;
    $nn = strlen($password);
    $c = strlen($strtodecrypt);

    while($i < $c) {
        $decrypted_string .= substr($ralphabet,strpos($pos_alpha_ary[$n],substr($strtodecrypt,$i,1)),1);
        $n++;
        if($n == $nn) $n = 0;
        $i++;
    }

    $decrypted_string = str_replace("[tab]","\t", $decrypted_string);
    $decrypted_string = str_replace("[new]","\n", $decrypted_string);
    $decrypted_string = str_replace("[ret]","\r", $decrypted_string);

    return $decrypted_string;
}
function mimeHeaderDecode($string) {

    if (strstr($string, "=?")) {
        $explode = explode("=?", $string);
	$newString = $explode[0];
	for ($i = 1; $i < sizeof($explode); $i++) {
	    $explode2 = explode("?", $explode[$i]);
            $newString .= (strtolower($explode2[1]) == "q") ? quoted_printable_decode($explode2[2]) : base64_decode($explode2[2]);
            $newString .= substr($explode2[3], 1);
	}
    }
    else return $string;

    return $newString;
}
function sub_mail($mail_content, $attmail){
    $mail_content_array = preg_split("/\n\n/", $mail_content);
    $header = array_shift ($mail_content_array);
    $body = join ( "\n\n", $mail_content_array);
    $header_s = preg_replace('/\n/', " ", $header);
    $boundary_s = preg_replace('/^.*boundary=(\S*) *.*/', "\$1", $header_s);
    $boundary_s = preg_replace('/"(.*)".*/', "\$1", $boundary_s);
    if($GLOBALS[debug_s] >= "3"){echo "BOUNDARY=$boundary_s\n<br>\n\n";}
    if ($boundary_s){
       list($body) = split ("--$boundary_s--", $body);
       $parts = split ("--$boundary_s", $body);
    }
    else{
       $parts[0] = $body;
    }
    $GLOBALS[link_add] .= "&attmail=$attmail";
    $GLOBALS[header] = $header;
    $GLOBALS[parts] = $parts;
}
function calc_size($size){
    $label = $GLOBALS[s_b];
    if ($size > 512){
        $size = $size / 1024;
        $label = $GLOBALS[s_kb];
        if ($size > 1024){
            $size = $size / 1024;
            $label = $GLOBALS[s_mb];
        }
    }
    $size = number_format($size, 1);
    $size = str_replace(".", $GLOBALS[s_decimalDelimiter], $size);
    $size = "$size $label";
    return $size;
}
if (!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return "N/A";
    }
}
if (!function_exists('memory_get_peak_usage')) {
    function memory_get_peak_usage() {
        return "N/A";
    }
}
?>