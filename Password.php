<?php 
error_reporting(E_ALL & ~E_NOTICE);
include("config-mail.php");
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"style/$stylesheet.css\">\n";
//MySQL database connection information 
//MySQL connection information: 
$mysql_host = "localhost"; //Host Name 
$mysql_user = "root"; //Username 
$mysql_pass = ""; //Password 
$mysql_db = "mail"; //Database 

//General Configuration: 
$form_title = "Sign Up"; //Name for this form 
$account_max = "900";  //Maximum size per account (1000000 = 1MB -> Do not uses spaces or commas!) 
$admin_notify = "1"; //1 = yes & 0 = no 
$admin_email = ""; //Administrators email to send notifications) 
$admin_default_activate = "0"; //1 = yes & 0 = no -> If no, the administrator has to authorise the account 
$accountisad = 0; // account is AD-account? 0 = no (default), 1 = yws;
$encryption = 0; //Password encryption level - 2 means md5;
$domain = 'popmail.com'; // the domain, where the account should be added too

$serv_webmail_url = ''; // full URL to the webmail-application

$url = $_SERVER["HTTP_HOST"]; 
$domain = (empty($domain))? $_SERVER["HTTP_HOST"] : $domain; 
$domain = (substr($domain,0,4) == "www.")? strstr($domain, ".") : $domain;

// Protect database entries and use MD5 encryption 
$strName    = (isset($_POST['name']))? addslashes( $_POST['name'] ) : ''; 
$strUser    = (isset($_POST['user']))? addslashes( $_POST['user'] ) : ''; 

$strDomain  = addslashes( "$domain" ); 
$strPass1   = (isset($_POST['pass1']))? addslashes( $_POST['pass1'] ) : ''; 

//Connect to database using information from above 
$open = mysql_connect($mysql_host, $mysql_user, $mysql_pass); 
$select = mysql_select_db($mysql_db); 

if (!$open || !$select) 
{ 
  echo "Unable to open database, contact your administrator!"; 
} else { 
  echo "<font size=\"+1\"><center>$form_title</center></font><br><br>"; 
   
  if ( isset($_POST['submit']) &&  $_POST['submit'] == 'create account' )
  { 
    $domain_res = mysql_query("SELECT * FROM `hm_domains` WHERE `domainname` = '{$strDomain}' LIMIT 1"); 
      if (!$domain_res) 
      {
        die('Error while selecting data: ' . mysql_error());
       }
      $domain_info = mysql_fetch_array($domain_res, MYSQL_ASSOC);

    $account_res = mysql_query("SELECT * FROM `hm_accounts` WHERE `accountaddress` = '{$strUser}@$strDomain' LIMIT 1"); 
    $v1 = mysql_num_rows($account_res); 
        
    If ($v1 >= 1) 
    { 
       echo "A subscriber with this email already exists!<br><br>Please <a href=\"javascript:history.go(-1)\">click here</a> to go back."; 
       return false;
    } else { 
      if ( !$_POST['pass1'] || !$_POST['pass2'] ) 
      { 
         echo "You did not enter both passwords!<br><br>Please <a href=\"javascript:history.go(-1)\">click here</a> to go back."; 
      } 
      elseif ( $_POST['pass1'] != $_POST['pass2'] ) 
      { 
         echo "The passwords entered do not match!<br><br>Please <a href=\"javascript:history.go(-1)\">click here</a> to go back."; 
      } else { 
         $domain_id = $domain_info['domainid'];
         $account_pass = ($encryption == 2)? md5( $strPass1 ) : $strPass1;
         
        $account_add = mysql_query("INSERT INTO `hm_accounts` (`accountdomainid`,`accountaddress`,`accountpassword`,`accountactive`,`accountisad`,`accountmaxsize`,`accountpwencryption`) VALUES ('$domain_id','$strUser@$strDomain','$account_pass','$admin_Default_activate','$accountisad','$account_max','$encryption')"); 
      } 
    } 
    
    if (!$account_add) 
      {
        die('<br><br>Database error, unable to add account. Please contact your administrator!<br> ' . mysql_error());
       }
        
    If ($admin_Default_activate == 0) 
    { 
       echo "Your account has been created but <b>requires activation</b> by administration."; 
      echo "Once your application has been approved, you will receive a confirmation message.<br><br>"; 
    } else { 
      echo "Your account has been created and is ready for use!<br><br>"; 
      echo "Username: $strUser@$strDomain<br><br>"; 
      if ( !empty($serv_webmail_url) ) echo "Webmail: <a href=\"$serv_webmail_url\">www.$strDomain/webmail</a><br>"; 
      echo "POP3: mail.$strDomain<br>"; 
      echo "IMAP: mail.$strDomain<br><br>"; 
      echo "SMTP: mail.$strDomain"; 
        
      $subject = "Welcome To $strDomain Email!"; 
      $message = "This is confirmation that your account has been created. You may now login to your account and start using it."; 

      mail("{$_POST['user']}@$strDomain", $subject, $message); //Send welcome message 
    } 
        
    if ($admin_Default_activate == 0 && $admin_notIfy == 1) 
    { 
      $admin_mail_subject = "Account Requires Activation!"; 
      $admin_mail_message = "The email account {$_POST['user']}@$strDomain has been created by {$_POST['name']} and requires administration activation!\r\n\r\nPlease login to the admin control panel to verIfy and activate user account.\r\n\r\n"; 
      mail($admin_email, $admin_mail_subject, $admin_mail_message); 
    } 
    elseif ($admin_Default_activate == 1 && $admin_notIfy == 1) 
    { 
      $admin_mail_subject = "New Account Created!"; 
      $admin_mail_message = "{$_POST['user']}@$strDomain has been created by {$_POST['name']}!"; 
      mail($admin_email, $admin_mail_subject, $admin_mail_message); 
    } 
  }
}

  echo " 
     <table align=\"center\" width=\"350\" border=\"0\" cellpadding=\"0\" cellspacing=\"2\"> 
    <form action=\"\" method=\"POST\"> 
       <tr><td>Full Name:</td><td><input type=\"text\" name=\"name\" size=\"21\"></td></tr> 
      <tr><td>E-Mail:</td><td><input type=\"text\" name=\"user\" size=\"21\"> @ ".$domain."</td></tr> 
      <tr><td>Password:</td><td><input type=\"password\" name=\"pass1\" size=\"21\"></td></tr> 
      <tr><td>Password:</td><td><input type=\"password\" name=\"pass2\" size=\"21\"> (For Confirmation)</td></tr> 
      <tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"create account\" style=\"width: 98%\"></td></tr> 
    </form> 
    </table>"; 
?>