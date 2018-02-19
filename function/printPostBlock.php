<?php
  function printPostBlock($postID,$mysqli){
    $stmt = $mysqli->prepare("select title,abstract,content,user.username,time from post join user on (post.userID = user.id) where post.id = ?");
    if(!$stmt){
      echo "<div class=\"post_not_exist\">The story does't exist</div>";
    }else{
      $stmt->bind_param('s', $postID);
      $stmt->execute();
      $stmt->bind_result($post_block_title,$post_block_abstract,$post_block_content,$post_block_username,$post_block_time);
      if(!$stmt->fetch()){
        echo "<div class=\"post_block_not_exist\">The story does't exist</div>";
      }else{
        echo "<div class=\"post-block\">";
        printf("<h2><a href=\"post.php?id=%d\">%s</a></h2>",$postID,htmlentities($post_block_title));
        printf("<div class=\"author\">by <b>%s</b> | last edit on %s</div>",htmlentities($post_block_username),htmlentities($post_block_time));
        printf("<div class=\"abstract\">%s</div>",htmlentities($post_block_abstract));
        echo "</div>";
      }
    }
    $stmt->close();
  }
?>
