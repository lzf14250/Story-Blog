<?php
    require('function/database.php');

    $usernameErr = "";
    $passwordErr = "";
    if(isset($_POST['username']))
    {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $username_not_empty = 0;
    $password_not_empty = 0;
    
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if(empty($username))
        {
            //username is empty
            $usernameErr = "Username is empty";
        }
        else
        {
            $username_not_empty = 1;
        }
        if(empty($password))
        {
            $passwordErr = "Password is empty";
        }
        else
        {
            $password_not_empty = 1;
        }
        if($username_not_empty && $password_not_empty)
        {
            //username is not empty， check if the username is valid
            $stmt = $mysqli->prepare("SELECT * FROM user WHERE username=?");
            $stmt->bind_param('s',$username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            if(empty($row))
            {
                //can't find the username
                $usernameErr = "The username not exists";
            }
            else if(password_verify($password,$row["password"]))
            {
                //username found, password is correct
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $row["id"];
                $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
                header("refresh:2;url=index.php");
                echo "<script type=\"text/javascript\">alert(\"Successfully login !\");</<cript>";
            }
            else
            {
                //password not match
                $passwordErr = "Password is incorrect!";
            }

        }
    }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>login.php</title>
        <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
        <?php require('function/printHead.php');?>
        <div class=body>
            <p><h1>Welcome to blog TEMP !</h1></p>
        <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
            <label for=username>username:</label>
            <input type=text name=username id=username>
            <span style="color:#ff0000;">* <?php echo $usernameErr;?></span>
            <br><br>
            <label for=password>password:</label>
            <input type=password name=password id=password>
            <span style="color:#ff0000;">* <?php echo $passwordErr;?></span>
            <br><br>
            <input type=submit value=login>
        </form>
        <p>click <a href="signup.html">HERE</a> if you don't have an account</p>
        </div>
    </body>
</html>
</body>

