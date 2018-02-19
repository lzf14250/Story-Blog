<?php
  session_start();
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
          if(isset($_GET['categoryID'])){
            $categoryID = (int)$_GET['categoryID'];
            $stmt = $mysqli->prepare("select name from category where id = ?");
            if(!$stmt){
              print("Can't access categorys");
            }else{
              $stmt->bind_param('s', $categoryID);
              $stmt->execute();
              $stmt->bind_result($category_name);
              if(!$stmt->fetch()){
                print("Wrong category id");
              }else{
                printf("<div class=\"category-title\">Category: %s</div>",$category_name);

                $stmt->close();
                $stmt = $mysqli->prepare("select count(*) from post where categoryID = ".$categoryID);
                if(!$stmt){
                  print("Can't access categorys");
                }else{
                  $stmt->execute();
                  $stmt->bind_result($postCount);
                  $stmt->fetch();
                  if($postCount==0){
                    echo "<div class=\"post_not_exist\">There's no stories now, waiting for yours ^_^</div>";
                  }else{
                    $stmt->close();
                    $stmt = $mysqli->prepare("select id from post where categoryID = ? order by -time");
                    if(!$stmt){
                      echo "<div class=\"post_not_exist\">There's no stories now, waiting for yours ^_^</div>";
                      print("Can't access categorys");
                    }else{
                      $stmt->bind_param('s', $categoryID);
                      $stmt->execute();
                      $resultPosts = $stmt->get_result();
                      require("function/printPostBlock.php");
                      while($row = $resultPosts->fetch_assoc()){
                        printPostBlock($row['id'],$mysqli);
                      }
                    }
                  }
                }
              }
            }
            $stmt->close();
          }else{
            print("Wrong category id");
          }
        ?>
      </div>
      <?php require("function/printSide.php");?>
    </div>
  </body>
</html>
