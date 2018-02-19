<!DOCTYPE html>
<html lang='en'>
<head>
        <meta charset='utf-8'>
        <title>New Post</title>
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
                echo "Login first to edit or create a post";
                exit;
              }
              $title = "";
              $categoryID =1;//default category 1 (others)
              $abstract = "";
              $content = "";
              $titleErr = "";
              $contentErr = "";
              $update = "false";

              if ($_SERVER["REQUEST_METHOD"] == "POST"){
                if(!isset($_POST['token'])||!hash_equals($_SESSION['token'],$_POST['token'])){
                  echo "Request forgery detected";
                  exit;
                }
                $title = isset($_POST['title'])?$_POST['title']:"";
                $categoryID =isset($_POST['category'])?(int)$_POST['category']:1;//default category 1 (others)
                $abstract = isset($_POST['abstract'])?$_POST['abstract']:"";
                $content = isset($_POST['content'])?$_POST['content']:"";
                //check if the input is empty
                if($title==""){
                  $titleErr = "You must add a title";
                }
                if($content==""){
                  $contentErr = "Content can not be empty";
                }
                if($abstract==""){
                  $abstract = substr($content,0,50);
                }
                if ($title!=""&&$content!=""){
                  //input is valid, store the input into database
                  if($_POST['update']=="false"){
                    $stmt = $mysqli->prepare("insert into post (title,abstract,content,userID,categoryID) values (?, ?, ?, ?, ?)");
                    if(!$stmt){
                      echo "Database Connection Error1";
                      exit;
                    }
                    $stmt->bind_param('sssii',$title,$abstract,$content,$user_id,$categoryID);
                    $stmt->execute();
                    $stmt->close();
                    echo "Successfully posted!";
                    header("refresh:2;url=user.php");
                    exit;
                  }else{
                    if(!isset($_POST['postID'])){
                      echo "Invalid story id";
                      exit;
                    }
                    $postID = (int)$_POST['postID'];
                    $stmt = $mysqli->prepare("update post set title=?, abstract=?, content=?, categoryID=? where id = ".$postID);
                    if(!$stmt){
                      echo "Database Connection Error2";
                      exit;
                    }
                    $stmt->bind_param('sssi',$title,$abstract,$content,$categoryID);
                    $stmt->execute();
                    $stmt->close();
                    echo "Successfully updated!";
                    header("refresh:2;url=user.php");
                    exit;
                  }

                }
              }
              if($_SERVER["REQUEST_METHOD"]=="GET"&&isset($_GET['edit'])&&isset($_GET['id'])){
                $postID = (int)$_GET['id'];
                $stmt = $mysqli->prepare("select userID from post where id = ".$postID);
                if($stmt){
                  $stmt->execute();
                  $selectUserID = 0;
                  $stmt->bind_result($selectUserID);
                  $stmt->fetch();
                  if($selectUserID!=$user_id){
                    echo "You can only edit your own stories";
                    exit;
                  }
                }
                $stmt->close();
                $stmt = $mysqli->prepare("select title,abstract,content,userID,categoryID from post where id = ".$postID);
                if(!$stmt){
                  echo "database connection error3";
                  exit;
                }
                $stmt->execute();
                $postOld = $stmt->get_result();
                $row = $postOld->fetch_assoc();
                $title = $row['title'];
                $abstract = $row['abstract'];
                $content = $row['content'];
                $categoryID = $row['categoryID'];
                $stmt->close();
                $update = "true";
              }
          ?>
          <h2>New Post</h2>
          <form method=post action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
                  <label for=title>Title:</label>
                  <input type="hidden" name="update" value="<?php echo htmlentities($update)?>">
                  <input type="hidden" name="postID" value="<?php echo htmlentities($postID)?>">
                  <input type="hidden" name="token" value="<?php echo htmlentities($_SESSION['token'])?>">
                  <input type="text" name="title" id="title" value="<?php echo htmlentities($title)?>">
                  <span style="color:#ff0000;">* <?php echo $titleErr;?></span>
                  <br><br>
                  <label>Category:</label>
                  <select name="category">;
                  <?php
                    $stmt = $mysqli->prepare("select id,name from category");
                    if($stmt){
                      $stmt->execute();
                      $stmt->bind_result($categoryIDs,$categoryNames);
                      while ($stmt->fetch()) {
                        if($categoryID==$categoryIDs){
                          printf("<option value=%d selected=\"selected\">%s</option>",$categoryIDs,$categoryNames);
                        }else{
                          printf("<option value=\"%d\">%s</option>",$categoryIDs,$categoryNames);
                        }
                      }
                      $stmt->close();
                    }
                  ?>
                  </select>
                  <br><br>
                  <label for=abstract>Abstract:</label><br>
                  <textarea id=bastract name="abstract" rows="5" cols="40"><?php echo htmlentities($abstract)?></textarea>
                  <br><br>
                  <label for=content>Content:</label>
                  <span style="color:#ff0000;">* <?php echo $contentErr;?></span>
                  <br><br>
                  <textarea id=content name="content" rows="30" cols="80"><?php echo htmlentities($content)?></textarea>
                  <br><br>
                  <input class='add-post' type="submit" name="submit" value="Post">
          </form>
        </div>
</body>
</html>
