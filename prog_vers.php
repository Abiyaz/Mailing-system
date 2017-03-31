<?php

$dirs = array("./", "filter/", "language/", "style/");
ini_set("error_reporting","E_ERROR");

echo "\n\n<br><br><br>\n\n<center>\n<h3>Release Version: $version</h3>\n<table border=0 cellspacing=1 cellpadding=3>\n";
echo "<tr><td class=tableheader><b> Filename: <td class=tableheader><b> Vers: <td class=tableheader><b> Last Changes: <td class=tableheader><b> Size: </td></tr>";
foreach($dirs as $dir_now){
    $filenames = array();
    $statement = "Files in $dir_now:";
    echo "<tr><td class=list colspan=4><b>$statement</b></td></tr>";
    $cnt = -1;
    if ($dir = @opendir($dir_now)) {
       while (($file = readdir($dir)) !== false) {
          $cnt++;
          if (ereg(".php", $file) || ereg(".css", $file)){
          	$filenames[$cnt] = $file;
          }
       }
    }
    closedir($dir);
    asort ($filenames);
    foreach($filenames as $file){
       if(!$file){ break 1; }
       if($fh1 = fopen ("$dir_now$file", "r")){
           while (!feof($fh1)) {
              $line = fgets($fh1, 4096);
              if(ereg("version_internal", $line)){
                 $file_version[$file] = preg_replace("/^.*\"(.*)\".*/", "\$1", $line);
                 break 1;
              }
           }
       }
       else{ $file_version[$file] = "err"; }
       $file_size[$file] = filesize($dir_now.$file);
       fclose ($fh1);
       $file_change_time[$file] = date(" d.m.Y @ H:i:s ", filemtime($dir_now.$file));
       $tag = "<i>";
       if (ereg("index", $file)){$tag = "<u>";}
       echo "<tr><td class=list> &nbsp; &nbsp; $tag $file ";
       echo "<td class=list>&nbsp; $tag $file_version[$file] ";
       if($file_version[$file] == "err"){ $file_error = "\n<br><b>an error happend.</b><br>\n"; }
       echo "<td class=list> $tag $file_change_time[$file] ";
       echo "<td class=list align=right> $tag $file_size[$file] ";
    }
}
echo "</table>\n$file_error</center>\n\n";
?>