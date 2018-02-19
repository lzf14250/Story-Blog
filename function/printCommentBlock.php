<?php
  function printCommentBlock($commentID,$mysqli){
    $stmt = $mysqli->prepare("select content,user.username,time from comment join user on (comment.userID = user.id) where comment.id = ?");
    if(!$stmt){
      echo "<div class=\"post_not_exist\">The story does't exist</div>";
    }else{
      $stmt->bind_param('s', $commentID);
      $stmt->execute();
      $stmt->bind_result($comment_content,$comment_username,$comment_time);
      if(!$stmt->fetch()){
        echo "The comment does't exist";
      }else{
        echo "<div class=\"comment-block\">";
        printf("<div class=\"comment-author\"><b>%s</b> says:</div>",htmlentities($comment_username));
        printf("<div class=\"comment-time\">on %s</div>",htmlentities($comment_time));
        printf("<div class=\"comment-content\">%s</div>",htmlentities($comment_content));
        echo "</div>";
      }
    }
    $stmt->close();
  }
?>
