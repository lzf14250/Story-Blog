<div class="head">
  <div class="title" onclick="location.href='index.php'">
    Stories
  </div>
  <div class="account">
    <?php
      if (!isset($_SESSION)){
        session_start();
      }
      if(!isset($_SESSION['username'])||!isset($_SESSION['id'])){
        echo "<button onclick=\"location.href='login.php'\">log in</button>";
        echo "<button onclick=\"location.href='signup.html'\">sign up</button>";
      }else{
        echo "<button onclick=\"location.href='user.php'\">".htmlentities($_SESSION['username'])."</button>";
        echo "<button onclick=\"location.href='logout.php'\">log out</button>";
      }
    ?>
  </div>
</div>
