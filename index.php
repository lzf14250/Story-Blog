<?php
  require('function/database.php');
?>
<!Doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Stories</title>
    <link rel="stylesheet" href="style.css" type="text/css">
  </head>
  <body>
    <?php require('function/printHead.php');?>
    <div class="body">
      <div class="main">
        <?php
          $stmt = $mysqli->prepare("select count(*) from post");
          if(!$stmt){
            echo "<div class=\"post_not_exist\">Cannot access stories</div>";
          }else{
            $stmt->execute();
            $stmt->bind_result($postCount);
            $stmt->fetch();
            if($postCount==0){
              echo "<div class=\"post_not_exist\">There's no stories now, waiting for yours ^_^</div>";
            }else{
              $stmt->close();
              $stmt = $mysqli->prepare("select id from post order by -time");
              if(!$stmt){
                echo "<div class=\"post_not_exist\">Cannot access stories</div>";
              }else{
                $stmt->execute();
                $resultPosts = $stmt->get_result();
                require("function/printPostBlock.php");
                while($row = $resultPosts->fetch_assoc()){
                  printPostBlock($row['id'],$mysqli);
                }
              }
            }
          }
          $stmt->close();
        ?>
      </div>
      <?php require("function/printSide.php");?>
    </div>
  </body>
</html>
