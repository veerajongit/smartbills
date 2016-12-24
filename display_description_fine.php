<?php
/**
 * Created by PhpStorm.
 * User: veeraj
 * Date: 11/7/16
 * Time: 11:35 AM
 */


if(isset($_REQUEST['deleteitem'])){
    include "db.php";
    echo $sql = "DELETE FROM fine_description WHERE srno=".$_REQUEST['srno'];
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->query($sql);
    exit;
}

if(isset($_REQUEST['table'])) {
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM fine_description WHERE billno='" . $_REQUEST['billno'] . "' ORDER BY srno";
$result = $conn->query($sql);

if ($result->num_rows > 0) { ?>
<table class="table"><tr><td>Item Name</td><td>Item Rate</td><td>Quantity</td><td>Weight</td><td>Amount</td><td></td></tr>
    <?php
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?= $row['item_name'] ?></td>
            <td><?=$row['item_rate']?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['weight'] ?></td>
            <td><?php echo $row['quantity']*$row['item_rate']; ?></td>
            <td>
                <button type="button" class="btn btn-danger" onclick="deleteitem(<?=$row['srno']?>)">Delete</button></td>
            </td>
        </tr>
        <?php
    }
    }
    exit;
    }
    ?>

    <?php

    if(isset($_REQUEST['totalamount'])) {
        include "db.php";
// Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM fine_description WHERE billno='" . $_REQUEST['billno'] . "' ORDER BY srno";
        $result = $conn->query($sql);
        $totalamount = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $totalamount  = $totalamount+($row['quantity']*$row['item_rate']);
            }
        }
        echo $totalamount;
        exit;
    }

    if(isset($_REQUEST['itemname']) && isset($_REQUEST['quantity']) && isset($_REQUEST['weight']) &&isset($_REQUEST['billno']) ){
        include "db.php";
// Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        echo $sql = "SELECT * FROM item_list WHERE item_name='" . $_REQUEST['itemname']."'";
        $result = $conn->query($sql);
        $totalamount = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo $sql2 = "
            INSERT INTO `fine_description` (`srno`, `billno`, `item_name`, `quantity`, `weight`, `item_rate`, `labour`, `creation_date`) VALUES 
            (NULL, '".$_REQUEST['billno']."', '".$_REQUEST['itemname']."', '".$_REQUEST['quantity']."', '".$_REQUEST['weight']."',
             '".$row['item_price']."', '".$row['item_labour']."', CURRENT_TIMESTAMP);
            ";
                $conn->query($sql2);
            }
        }
        exit;
    }
    ?>
