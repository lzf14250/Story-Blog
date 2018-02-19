<!DOCTYPE html>
<html lang='en'>
<head>
        <meta charset='utf-8'>
        <title>Stories</title>
        <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
        <?php require('function/printHead.php');?>
        <div class="body">
          <?php
              require('function/database.php');
              // define variables and set to empty values
              if(isset($_SESSION['username'])&&isset($_SESSION['id'])){
                $username = $_SESSION['username'];
                $user_id = $_SESSION['id'];
              }else{
                echo "Login first to edit or create a comment";
                exit;
              }
              $content = "";
              $contentErr = "";
              $update = "false";

              if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if(!isset($_POST['token'])||!hash_equals($_SESSION['token'],$_POST['token'])){
                  echo "Request forgery detected";
                  exit;
                }
                $content = isset($_POST['content'])?$_POST['content']:"";
                //check if the input is empty
                if($content==""){
                  $contentErr = "Content can not be empty";
                }
                if ($content!=""){
                  //input is valid, store the input into database
                  //var_dump($_POST['update']);
                  if($_POST['update']=="false"){
                    if(!isset($_POST['postID'])){
                      echo "Invalid story id";
                      exit;
                    }
                    $postID = (int)$_POST['postID'];
                    $stmt = $mysqli->prepare("insert into comment (content,userID,postID) values (?, ?, ?)");
                    if(!$stmt){
                      echo "Database Connection Error";
                      exit;
                    }
                    $stmt->bind_param('sii',$content,$user_id,$postID);
                    $stmt->execute();
                    $stmt->close();
                    echo "Successfully posted!";
                    header("refresh:2;url=user.php");
                    exit;
                  }else{
                    if(!isset($_POST['commentID'])){
                      echo "Invalid comment id";
                      exit;
                    }
                    $commentID = (int)$_POST['commentID'];
                    $stmt = $mysqli->prepare("update comment set content=? where id = ".$commentID);
                    if(!$stmt){
                      echo "Database Connection Error";
                      exit;
                    }
                    $stmt->bind_param('s',$content);
                    $stmt->execute();
                    $stmt->close();
                    echo "Successfully updated!";
                    header("refresh:2;url=user.php");
                    exit;
                  }
                }
              }
              if($_SERVER["REQUEST_METHOD"]=="GET"&&isset($_GET['edit'])&&isset($_GET['id'])){
                $commentID = (int)$_GET['id'];
                $stmt = $mysqli->prepare("select userID from comment where id = ".$commentID);
                if($stmt){
                  $stmt->execute();
                  $selectUserID = 0;
                  $stmt->bind_result($selectUserID);
                  $stmt->fetch();
                  if($selectUserID!=$user_id){
                    echo "You can only edit your own comments";
                    exit;
                  }
                }
                $stmt->close();
                $stmt = $mysqli->prepare("select content from comment where id = ".$commentID);
                if(!$stmt){
                  echo "database connection error";
                  exit;
                }
                $stmt->execute();
                $commentOld = $stmt->get_result();
                $row = $commentOld->fetch_assoc();
                $content = $row['content'];
                $stmt->close();
                $update = "true";
              }

              if($_SERVER["REQUEST_METHOD"]=="GET"&&isset($_GET['add'])&&isset($_GET['id'])){
                $postID = (int)$_GET['id'];
                $update = "false";
              }
          ?>
          <h2>New Post</h2>
          <form method=post action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
                  <input type="hidden" name="update" value="<?php echo htmlentities($update)?>">
                  <?php
                    if($update=="true"){
                      echo "<input type=\"hidden\" name=\"commentID\" value=\"".htmlentities($commentID)."\">";
                    }else{
                      echo "<input type=\"hidden\" name=\"postID\" value=\"".htmlentities($postID)."\">";
                    }
                  ?>
                  <input type="hidden" name="token" value="<?php echo htmlentities($_SESSION['token'])?>">
                  <label for=content>Comment:</label>
                  <span style="color:#ff0000;">* <?php echo $contentErr;?></span>
                  <br><br>
                  <textarea id=content name="content" rows="30" cols="80"><?php echo htmlentities($content)?></textarea>
                  <br><br>
                  <input class='add-post' type="submit" name="submit" value="Post">
          </form>
        </div>
</body>
</html>
