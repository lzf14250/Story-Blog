<?php
require('function/database.php');

if(!isset($_SESSION))
{
    session_start();
}
if(!hash_equals($_SESSION['token'], $_POST['token']))
{
    die("Request forgery detected");
}
else
{
$post_id = $_POST['id'];
$stmt = $mysqli->prepare("select * from post where id=?");
$stmt->bind_param('i',$post_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if($row['userID'] == $_SESSION['id'])
{
    $stmt->close();
    $stmt = $mysqli->prepare("delete from post where id=?");
    $stmt->bind_param('i',$post_id);
    $stmt->execute();
    echo "Successfilly deleted!";
}
else
{
    echo "You have no right to delete the post!";
}
header("refresh:2;url=user.php");
}
?>