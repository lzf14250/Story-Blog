<!DOCTYPE html>
    <head>
        <meta charset='utf-8'>
        <title>sign up</title>
    </head>
    <body>
        <?php
        require('function/database.php');
        
        $username=$_POST['username'];
        $password=$_POST['password_1'];
        $password_1=$_POST['password_1'];
        $password_2=$_POST['password_2'];
        if($username=="")
        {
            //username is empty
            echo "<p><h1>Please enter a username</h1></p>";
            echo "<p>Click <a href=signup.html>HERE</a> if not respond.</p>";
            header("refresh:2;url=signup.html");
        }
        else if(!preg_match('/^[\w_\-]+$/',$username))
        {
            //invalid username signup
            echo "Invalid username!";
            header("refresh:2;url=signup.html");
        }
        else if(strlen($username)>12)
        {
            //username is too long
            echo "<p><h1>Please enter a username limited within 12 characters</h1></p>";
            echo "<p>Click <a href=signup.html>HERE</a> if not respond.</p>";
            header("refresh:2;url=signup.html");
        }
        else if($password_1 != $password_2)
        {
            echo "<p><h1>The password not match</h1></p>";
            echo "<p>Click <a href=signup.html>HERE</a> if not respond.</p>";
            header("refresh:2;url=signup.html");
        }
        else
        {
            //username and password is valid
            $stmt = $mysqli->prepare("SELECT * FROM user WHERE username=?");
            $stmt->bind_param('s',$username);
            $stmt->execute();
            $search_result = $stmt->get_result();
            $row = $search_result->fetch_assoc();
            $stmt->close();   //close the $stmt
            if(!empty($row))
            {
                //username has already existed
                echo "<h1>This username has already existed</h1>";
                echo "<p>Click <a href=signup.html>HERE</a> if not respond.</p>";
                header("refresh:2;url=signup.html");
            }
            else
            {
                //sign up a new username and hash the password
                $options = [
                    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM), //add the random salt 
                ];
                $password_hashed = password_hash($password, PASSWORD_BCRYPT, $options);
                $stmt = $mysqli->prepare("insert into user (username,password) values (?, ?)");
                $stmt->bind_param('ss',$username,$password_hashed);
                $stmt->execute();
                $stmt->close();
                
                //check if signed up successfully
                $stmt = $mysqli->prepare("SELECT * FROM user WHERE username=?");
                $stmt->bind_param('s',$username);
                $stmt->execute();
                $search_result = $stmt->get_result();
                $row = $search_result->fetch_assoc();
                $stmt->close();   //close the $stmt
                if(!empty($row))
                {
                    echo "<p>Successfully sign up!</p>";
                    echo "<p>Click <a href=index.php>HERE</a> if not respond.</p>";
                    header("refresh:2;url=index.php");
                }
                else
                {
                    echo "<p>Sorry, Failed to sign up.</p>";
                    echo "<p>Click <a href=signup.html>HERE</a> if not respond.</p>";
                    header("refresh:2;url=signup.html");
                }
            }
        }
        ?>
    </body>