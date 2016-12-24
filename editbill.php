<?php
/**
 * Created by PhpStorm.
 * User: Veeraj Shenoy
 * Date: 24-12-2016
 * Time: 09:41 PM
 */
include "session.php";
if(!isset($_GET['billno'])){ header('location:salesbills.php'); }
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['billno']) && isset($_POST['buyersname']) && isset($_POST['amount']) && isset($_POST['othercharges']) && isset($_POST['vat']) && isset($_POST['totalamount'])){
    include "db.php";
// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM `address` WHERE companyname='".$_POST['buyersname']."'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $sql = "DELETE FROM `".$table_name."_bill` WHERE srno=".$_POST['srno'];
            $conn->query($sql);
            $sql3 = "
            INSERT INTO `".$table_name."_bill` (`srno`, `bill_no`, `buyer_name`, `buyer_address`, `amount`, `vat`, `other_charges`, `sell_date`, `creation_date`) VALUES 
            (".$_POST['srno'].", '".$_POST['billno']."', '".$_POST['buyersname']."', '".$row['address']."', '".$_POST['amount']."', '".$_POST['vat']."',
             '".$_POST['othercharges']."', '".date('Y-m-d', strtotime($_POST['date']))."', CURRENT_TIMESTAMP);
            ";
            $conn->query($sql3);
        }
    }
    $conn->close();
    header('location:salesbills.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SmartBills - Sales Bills</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
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
                "order": [[ 0, "desc" ]]
            });
        } );
    </script>
</head>
<body>
<?php include "navbar.php"; ?>
<form method="post">
    <div class="container" style="margin-bottom:100px" id="table">
        <?php
        $sql = "SELECT * FROM ".$table_name."_bill WHERE srno=".$_GET['billno'];
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-md-12">
                    <div class="col-md-3">
                        <input class="hidden" name="srno" id="srno" value="<?=$row['srno']?>" required>
                        <b>Bill No :</b>
                        <input class="form-control" value="<?=$row['bill_no']?>" name="billno" id="billno" required>
                    </div>

                    <div class="col-md-3">
                        <b>Bill Date :</b>
                        <input class="form-control" value="<?=date('d-m-Y', strtotime($row['sell_date']))?>" name="date" id="date" required>
                    </div>

                    <div class="col-md-3">
                        <b>Company Name :</b>
                        <select class="form-control" id="buyersname" name="buyersname" required>
                            <option value="<?=$row['buyer_name']?>"><?=$row['buyer_name']?></option>
                            <?php

                            $sql3 = "SELECT * FROM address ORDER BY companyname";
                            $result3 = $conn->query($sql3);

                            if ($result3->num_rows > 0) {
                                // output data of each row
                                while ($row3 = $result3->fetch_assoc()) {
                                    if ($row['buyer_name'] != $row3['companyname']) { ?>
                                        ?>
                                        <option value="<?= $row3['companyname'] ?>"><?= $row3['companyname'] ?></option>
                                    <?php }
                                }
                            }?>
                        </select>
                    </div>

                </div>
                <hr style="margin: 15px">
                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="itemname">Item Name:</label>
                            <select class="form-control" id="itemname" name="itemname">
                                <?php
                                include "db.php";
                                // Create connection
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $sql3 = "SELECT * FROM `item_list` ORDER BY item_name";
                                $result3 = $conn->query($sql3);

                                if ($result3->num_rows > 0) {
                                    // output data of each row
                                    while ($row3 = $result3->fetch_assoc()) {
                                        ?>
                                        <option value="<?=$row3['item_name']?>"><?=$row3['item_name']?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" min="0" value="0" class="form-control" id="quantity" step="any" name="quantity">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="weight">Weight:</label>
                            <input type="number" min="0" value="0" class="form-control" id="weight" step="any" name="weight">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" onclick="submitdescription();" class="btn btn-info" style="margin-top: 24px">Add Item</button>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Item List</h4>
                </div>
                <div class="col-md-12">
                    <div style="margin-bottom: 250px" id="description"><p style="padding-top: 10px; text-align: center; color: red">No Details added yet</p></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input type="number" class="form-control" id="amount" step="any" name="amount" value="0" min="0" readonly required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="othercharges">Other Charges:</label>
                                <input type="number" onchange="calculate()" oninput="calculate()" class="form-control" min="0" value="<?=$row['other_charges']?>" id="othercharges" step="any" readonly name="othercharges" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vat">VAT in %age:</label>
                                <input type="number" onchange="calculate()" oninput="calculate()" class="form-control" min="0" value="<?=$row['vat']?>" id="vat" step="any" readonly name="vat" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="totalamount">Total Amount:</label>
                                <input type="number" class="form-control" id="totalamount" step="any" readonly name="totalamount" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <button onclick="location.href='salesbills.php' " class="btn btn-warning">Go Back</button>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</form>
<script>
    function submitdescription(){
        var itemname = document.getElementById('itemname').value;
        var quantity = document.getElementById('quantity').value;
        var weight = document.getElementById('weight').value;
        var billno = document.getElementById('srno').value;
        var sell_date = document.getElementById('date').value;

        var data = {
            itemname : itemname,
            quantity : quantity,
            weight : weight,
            billno : billno
        };
        $.post( "display_description_<?=$table_name?>.php", data)
            .done(function( data1 ) {
                refresh();
            });

    }

    function refresh(){
        var billno = document.getElementById('srno').value;
        var sell_date = document.getElementById('date').value;

        var data = {
            billno : billno,
            table : "yes"
        };

        $.post( "display_description_<?=$table_name?>.php", data)
            .done(function( data1 ) {
                $('#description').html(data1);
                totalamount();
            });
    }

    function totalamount(){
        var billno = document.getElementById('srno').value;
        var sell_date = document.getElementById('date').value;
        var data = {
            billno : billno,
            totalamount : "yes"
        };

        $.post( "display_description_<?=$table_name?>.php", data)
            .done(function( data1 ) {
                var number = parseFloat(data1.trim());
                if(number > 0) {
                    $('#amount').val(data1.trim());
                    $('#othercharges').attr('readonly', false);
                    $('#vat').attr('readonly', false);
                    calculate();
                }else{
                    $('#amount').val("0");
                    $('#othercharges').attr('readonly', true);
                    $('#vat').attr('readonly', true);
                    calculate();
                }
            });
    }

    $( function() {
        $( "#date" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );



    function calculate(){
        var amount = parseFloat($('#amount').val());
        var vat = parseFloat($('#vat').val());
        var othercharges = parseFloat($('#othercharges').val());

        $('#totalamount').val( ( (amount + othercharges)*(vat)/100 ) + amount + othercharges );
    }

    function deleteitem(srno){
        var data = {
            srno : srno,
            deleteitem : "yes"
        };
        $.post( "display_description_<?=$table_name?>.php", data)
            .done(function( data1 ) {
                refresh();
            });

    }


    $( document ).ready(function() {
        refresh();
    });
</script>
</body>
</html>

