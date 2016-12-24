<?php
/**
 * Created by PhpStorm.
 * User: veeraj
 * Date: 11/24/16
 * Time: 10:28 AM
 */

session_start();

$userid = $_SESSION['userid'];
$oldpwd = $_POST['oldpwd'];
$newpwd = $_POST['newpwd'];
$retypepwd = $_POST['retypepwd'];

include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT password FROM login WHERE srno = ".$userid;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $password = $row['password'];
    }
}

if($password != $oldpwd){
    exit;
}else{
    if($retypepwd == $newpwd) {
        $sql = "UPDATE login SET password = '".$retypepwd."' WHERE srno=".$userid;
        $conn->query($sql);
        echo "success";
        exit;
    }else{
        exit;
    }
}