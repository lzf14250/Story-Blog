<?php
  if (!isset($_SESSION)){
    session_start();
  }
  session_destroy();
  header("refresh:2;url=index.php");
  die('Logout Successfully');
?>
