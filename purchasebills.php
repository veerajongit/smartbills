<?php

include "session.php";

if(isset($_REQUEST['deletebill'])){
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "DELETE FROM purchase_".$table_name." WHERE srno=".$_REQUEST['deletebill'];
    $conn->query($sql);
    header('location:purchasebills.php');
}
if(isset($_POST['billno']) && isset($_POST['date']) && isset($_POST['sellersname']) && isset($_POST['amount']) && isset($_POST['vat']) && isset($_POST['othercharges']) && isset($_POST['totalamount'])){
    include "db.php";
// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO `purchase_".$table_name."` (`srno`, `bill_no`, `pur_date`, `party_name`, `total`, `tax`, `othercharges`, `grand_total`) 
    VALUES (NULL, '".$_POST['billno']."', '".date('Y-m-d', strtotime($_POST['date']))."', '".$_POST['sellersname']."', '".$_POST['amount']."', '".$_POST['vat']."', '".$_POST['othercharges']."', '".$_POST['totalamount']."');";
    if(isset($_POST['srno'])){
        $sql = "DELETE FROM  purchase_".$table_name." WHERE srno=".$_POST['srno'];
        $conn->query($sql);
        $sql = "INSERT INTO `purchase_".$table_name."` (`srno`, `bill_no`, `pur_date`, `party_name`, `total`, `tax`, `othercharges`, `grand_total`) 
    VALUES (NULL, '".$_POST['billno']."', '".date('Y-m-d', strtotime($_POST['date']))."', '".$_POST['sellersname']."', '".$_POST['amount']."', '".$_POST['vat']."', '".$_POST['othercharges']."', '".$_POST['totalamount']."');";
    }
    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header('location:purchasebills.php');
}

if(isset($_POST['srnopurchase'])){
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO `purchase_".$table_name."_paid` (`srno`, `srnoofpurchase_".$table_name."`, `paid_date`, `paid_amount`, `details`)
    VALUES (NULL, '".$_POST['srnopurchase']."', '".date('Y-m-d', strtotime($_POST['paiddate']))."', '".$_POST['paidamount']."', '".$_POST['details']."')";
    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header('location:purchasebills.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SmartBills - Purchase Bills</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <link href='css/opensans.css' rel='stylesheet' type='text/css'>
    <style>
        body{
            font-family: 'Open Sans';
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "pageLength": 50,
                "ordering": false
            });
        } );
    </script>
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container" style="margin-bottom:100px" id="table">
    <h2>Purchase Bills<div class="pull-right">
            <!-- Trigger the modal with a button -->
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add New</button>
        </div></h2>
    <table class="table table-bordered" id="example">
        <thead>
        <tr>
            <th>Bill No</th>
            <th>Date</th>
            <th>Sellers Name</th>
            <th>Amount</th>
            <th>Vat</th>
            <th>Other Charges</th>
            <th>Grand Total</th>
            <th>Paid</th>
            <th>Options</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php
        include "db.php";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM `purchase_".$table_name."` ORDER BY pur_date DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $total = 0;
                $total = (float)$row['total'] + (float)$row['tax'] + (float)$row['othercharges'];
                ?>
                <tr>
                    <td><?= $row['bill_no'] ?></td>
                    <td width="80px"><?= date("d-m-Y", strtotime($row['pur_date'])) ?></td>
                    <td><?= $row['party_name'] ?></td>
                    <td><?= $row['total'] ?></td>
                    <td><?= $row['tax'] ?></td>
                    <td><?= $row['othercharges'] ?></td>
                    <td><?= $total ?></td>
                    <?php
                    $sql2 = "SELECT SUM(paid_amount) FROM purchase_".$table_name."_paid WHERE srnoofpurchase_".$table_name."=" . $row['srno'];
                    $result2 = $conn->query($sql2);

                    if ($result2->num_rows > 0) {
                        // output data of each row
                        while ($row2 = $result2->fetch_assoc()) {
                            if($row2['SUM(paid_amount)'] == '') {
                                $float = 0;
                            }else{
                                $float = $row2['SUM(paid_amount)'];
                            }
                            ?>
                            <td align="center"><?= $float ?><br><button class="btn btn-sm btn-info" onclick="openmodal(<?=$row['srno']?>)">Add Payment</button></td>
                        <?php }
                    } else {
                        echo '<td><button class="btn btn-sm btn-info" onclick="openmodal('.$row['srno'].')">Add Payment</button></td>';
                    }
                    ?>
                    <td align="center">
                        <button type="button" class="btn btn-info" data-toggle="modal"
                                data-target="#myModal<?= $row['srno'] ?>">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                        </button>
                    </td>
                    <td align="center"><a href="purchasebills.php?deletebill=<?=$row['srno']?>"><button class="btn btn-default"><i class="fa fa-times" aria-hidden="true" style="color:red"></i></button></a></td>
                </tr>
                <?php
            }
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Purchase Details</h4>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="itemname">Bill No:</label>
                        <input type="text" class="form-control" id="billno" name="billno" required>
                    </div>
                    <div class="form-group">
                        <label for="itemprice">Date:</label>
                        <input type="text" class="form-control datepicker" id="date" name="date" value="<?=date('d-m-Y')?>" required>
                    </div>
                    <div class="form-group">
                        <label for="itemlabour">Sellers Name:</label>
                        <select class="form-control" id="sellersname" name="sellersname" required>
                            <?php
                            include "db.php";
                            $sql = "SELECT * FROM `bill`";
                            // Create connection
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            // Check connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql3 = "SELECT * FROM address ORDER BY companyname";
                            $result3 = $conn->query($sql3);

                            if ($result3->num_rows > 0) {
                                // output data of each row
                                while ($row3 = $result3->fetch_assoc()) {
                                    ?>
                                    <option value="<?=$row3['companyname']?>"><?=$row3['companyname']?></option>
                                <?php }
                            }?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="itemlabour">Amount:</label>
                        <input type="number" class="form-control" id="amount" step="any" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="itemlabour">VAT:</label>
                        <input type="number" class="form-control" id="vat" step="any" name="vat" required>
                    </div>
                    <div class="form-group">
                        <label for="itemlabour">Other Charges:</label>
                        <input type="number" class="form-control" id="othercharges" step="any" name="othercharges" required>
                    </div>
                    <div class="form-group">
                        <label for="itemlabour">Total Amount:</label>
                        <input type="number" class="form-control" id="totalamount" step="any" name="totalamount" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<?php
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `purchase_".$table_name."`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) { ?>
        <!-- Modal -->
        <div id="myModal<?= $row['srno'] ?>" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Purchase Details</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="number" class="hidden" name="srno" id="srno" value="<?= $row['srno'] ?>" required>
                            <div class="form-group">
                                <label for="itemname">Bill No:</label>
                                <input type="text" class="form-control" id="billno" name="billno" value="<?=$row['bill_no']?>" required>
                            </div>
                            <div class="form-group">
                                <label for="itemprice">Date:</label>
                                <input type="text" class="form-control datepicker" id="date" name="date" value="<?=date('d-m-Y', strtotime($row['pur_date']))?>" required>
                            </div>
                            <div class="form-group">
                                <label for="sellersname">Sellers Name:</label>
                                <select class="form-control" id="sellersname" name="sellersname" required>
                                    <option value="<?=$row['party_name']?>"><?=$row['party_name']?></option>
                                    <?php
                                    include "db.php";
                                    $sql = "SELECT * FROM `bill`";
                                    // Create connection
                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    // Check connection
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    $sql3 = "SELECT * FROM address ORDER BY companyname";
                                    $result3 = $conn->query($sql3);

                                    if ($result3->num_rows > 0) {
                                        // output data of each row
                                        while ($row3 = $result3->fetch_assoc()) {
                                            ?>
                                        <option value="<?=$row3['companyname']?>"><?=$row3['companyname']?></option>
                                        <?php }
                                    }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="itemlabour">Amount:</label>
                                <input type="number" class="form-control" id="amount" step="any" name="amount" value="<?=$row['total']?>" required>
                            </div>
                            <div class="form-group">
                                <label for="itemlabour">VAT:</label>
                                <input type="number" class="form-control" id="vat" step="any" name="vat" value="<?=$row['tax']?>" required>
                            </div>
                            <div class="form-group">
                                <label for="itemlabour">Other Charges:</label>
                                <input type="number" class="form-control" id="othercharges" step="any" value="<?=$row['othercharges']?>"
                                       name="othercharges" required>
                            </div>
                            <div class="form-group">
                                <label for="itemlabour">Grand Total:</label>
                                <input type="number" class="form-control" id="totalamount" step="any" name="totalamount" value="<?=$row['grand_total']?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-info">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
}
?>


<div id="myModalpurchase" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Paid Details</h4>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="number" class="hidden" name="srnopurchase" id="srnopurchase" required>
                    <div class="form-group">
                        <label for="itemprice">Paid Date:</label>
                        <input type="text" class="form-control datepicker" id="paiddate" name="paiddate" value="<?=date('d-m-Y')?>" required>
                    </div>
                    <div class="form-group">
                        <label for="itemlabour">Paid Amount:</label>
                        <input type="number" class="form-control" id="paidamount" step="any" name="paidamount" required>
                    </div>
                    <div class="form-group">
                        <label for="itemlabour">Payment Details:</label>
                        <input type="text" class="form-control" id="details" name="details" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal Finish-->

<script>
    $( function() {
        $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );

    function openmodal(a){
        document.getElementById('srnopurchase').value = a;
        $('#myModalpurchase').modal('show');
    }
</script>
</body>
</html>

