<?php
  require('function/database.php');
?>
<!Doctype html>
<html lang='en'>
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
          if(isset($_GET['id'])){
            $postID = (int)$_GET['id'];
            $stmt = $mysqli->prepare("select title,abstract,content,user.username,time from post join user on (post.userID = user.id) where post.id = ".$postID);
            if(!$stmt){
              echo "<div class=\"post_not_exist\">Cannot access stories</div>";
            }else{
              $stmt->execute();
              $stmt->bind_result($post_title,$post_abstract,$post_content,$post_username,$post_time);
              if(!$stmt->fetch()){
                echo "<div class=\"post_block_not_exist\">The story does't exist</div>";
              }else{
                echo "<div class=\"post\">";
                printf("<h2><a href=\"post.php?id=%d\">%s</a></h2>",$postID,htmlentities($post_title));
                printf("<div class=\"author\">by <b>%s</b> | last edit on %s</div>",htmlentities($post_username),htmlentities($post_time));
                printf("<div class=\"abstract\">%s</div>",htmlentities($post_abstract));
                printf("<div class=\"content\">%s</div>",htmlentities($post_content));
                echo "</div>";
              }
            }
            $stmt->close();
            //coment
            echo "<div class=\"comment-title\">Comments:</div>";
            if(isset($_SESSION['username'])){
              echo "<button class=\"comment-add\" onclick=\"location.href='editComment.php?add=true&id=".$postID."'\">write comment</button>";
            }
            $stmt = $mysqli->prepare("select count(*) from comment where postID = ".$postID);
            if(!$stmt){
              echo "Cannot access comments";
            }else{
              $stmt->execute();
              $stmt->bind_result($commentCount);
              $stmt->fetch();
              if($commentCount==0){
                echo "<div class=\"post_not_exist\">There's no comments now, waiting for yours ^_^</div>";
              }else{
                $stmt->close();
                $stmt = $mysqli->prepare("select id from comment where postID = ".$postID);
                if(!$stmt){
                  echo "Cannot access comments";
                }else{
                  $stmt->execute();
                  $commentsResult = $stmt->get_result();
                  require("function/printCommentBlock.php");
                  while($row = $commentsResult->fetch_assoc()){
                    printCommentBlock($row['id'],$mysqli);
                  }
                }
              }
            }
            $stmt->close();
          }else{
            printf("Wrong story id");
          }
        ?>
      </div>
      <?php require("function/printSide.php");?>
    </div>
  </body>
</html>
