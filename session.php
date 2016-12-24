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
}