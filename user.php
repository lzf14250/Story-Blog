<?php
    require('function/database.php');
?>

<!DOCTYPE html>
<head>
    <title>user.php</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <?php require('function/printHead.php');?>
    <div class=body>
        <div class="user-side">
        <a onclick="document.getElementById('main_post').style.display='block';document.getElementById('main_comment').style.display='none'">Posts</a>
        <a onclick="document.getElementById('main_post').style.display='none';document.getElementById('main_comment').style.display='block'">Comments</a>
        </div>
        <div class="user-main">
          <div id="main_comment" style="display:none">
            <?php
                if(!isset($_SESSION['username'])||!isset($_SESSION['id'])){
                  echo "Please login first";
                  exit;
                }
                $user_id = $_SESSION['id'];
                //print comments
                $stmt = $mysqli->prepare("select count(*) from comment where userID=?");
                if(!$stmt)
                {  //access failed
                    echo "<div class=\"post_not_exist\">Cannot access comments</div>";
                }
                else
                {
                    $stmt->bind_param('i',$user_id);
                    $stmt->execute();
                    $stmt->bind_result($commentCount);
                    $stmt->fetch();
                    if($commentCount==0)
                    {
                        echo "<div class=\"post_not_exist\">There's no comments now, waiting for yours ^_^</div>";
                    }
                    else
                    {
                        //user has comments
                        $stmt->close();
                        $stmt = $mysqli->prepare("select id from comment where userID=?");
                        if(!$stmt)
                        {
                            echo "<div class=\"post_not_exist\">Cannot access comments</div>";
                        }
                        else
                        {
                            $stmt->bind_param('i',$user_id);
                            $stmt->execute();
                            $resultComments = $stmt->get_result();
                            require("function/printCommentBlock.php");
                            while($row = $resultComments->fetch_assoc())
                            {
                                printCommentBlock($row['id'],$mysqli);
                                echo "<button class='block-button' onclick=\"location.href='editComment.php?edit=true&id=".(int)$row['id']."'\">edit</button>";
                                printf("<form method=post id=\"deleteComment_form\" action=\"deleteComment.php\">");
                                printf("<input type=\"hidden\" name=\"token\" value=\"".htmlentities($_SESSION['token'])."\" />");
                                printf("<input id=\"deleteComment_id\" type=\"hidden\" name=\"id\" />");
                                printf("<button class='block-button' onclick=\"deleteComment_id.value = %d ; deleteComment_form.submit()\">delete</button>",$row['id']);
                                printf("</form>");

                            }
                        }
                    }
                }
                $stmt->close();
            ?>
          </div>
          <div id="main_post">
            <?php
                //print stories
                printf("<button class='add-post' onclick=\"location.href='editPost.php'\">New Post</button>");
                $stmt = $mysqli->prepare("select count(*) from post where userID=?");
                if(!$stmt)
                {  //access failed
                    echo "<div class=\"post_not_exist\">Cannot access posts</div>";
                }
                else
                {
                    $stmt->bind_param('i',$user_id);
                    $stmt->execute();
                    $stmt->bind_result($postCount);
                    $stmt->fetch();
                    if($postCount==0)
                    {
                        echo "<div class=\"post_not_exist\">There's no posts now, waiting for yours ^_^</div>";
                    }
                    else
                    {
                        //user has story
                        $stmt->close();
                        $stmt = $mysqli->prepare("select id from post where userID=?");
                        if(!$stmt)
                        {
                            echo "<div class=\"post_not_exist\">Cannot access posts</div>";
                        }
                        else
                        {
                            $stmt->bind_param('i',$user_id);
                            $stmt->execute();
                            $resultPosts = $stmt->get_result();
                            require("function/printPostBlock.php");

                            while($row = $resultPosts->fetch_assoc())
                            {
                                printPostBlock($row['id'],$mysqli);
                                printf("<button class='block-button' onclick=\"location.href='editPost.php?edit=true&id=".(int)$row['id']."'\">edit</button>");
                                printf("<form method=post id=\"deletePost_form\" action=\"deletePost.php\">");
                                printf("<input type=\"hidden\" name=\"token\" value=\"".htmlentities($_SESSION['token'])."\" />");
                                printf("<input id=\"deletePost_id\" type=\"hidden\" name=\"id\" />");
                                printf("<button class='block-button' onclick=\"deletePost_id.value =%d ; deletePost_form.submit()\">delete</button>",$row['id']);
                                printf("</form>");
                            }
                        }
                    }
                }
                $stmt->close();
            ?>
          </div>
        </div>
    </div>
</body>
