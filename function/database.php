<?php
  $mysqli = new mysqli('localhost','grp','cuijinhao','blog');
  if($mysqli->connect_errno){
    printf("Database connection failed: %s",$mysqli->connect_errno);
  }
?>
