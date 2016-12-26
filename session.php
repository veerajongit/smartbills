<?php
/**
 * Created by PhpStorm.
 * User: veeraj
 * Date: 11/22/16
 * Time: 7:23 PM
 */

session_start();
if($_SESSION['work'] == 'precision'){
    $table_name='precision';
}

if($_SESSION['work'] == 'fine'){
    $table_name='fine';
}


if(!isset($_SESSION['work']) || !isset($_SESSION['userid'])){
    header('location:index.php');
}else{
	include "db.php";
	$sql = "INSERT INTO `smartbills`.`track` (`srno`, `userid`, `details`, `pagename`) VALUES (NULL, '".$_SESSION['userid']."', '".http_build_query($_REQUEST)."', '".basename($_SERVER['PHP_SELF'])."');";
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	$conn->query($sql);	
}
