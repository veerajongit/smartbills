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
    $sql = "DELETE FROM ".$table_name."_bill WHERE srno=".$_REQUEST['deletebill'];
    $conn->query($sql);
    header('location:salesbills.php');
}

if(isset($_POST['sample1']) && isset($_POST['sample2'])){
	include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "
		INSERT INTO `smartbills`.`".$table_name."_sample` (`srno`, `bill_srno`, `sample1`, `sample2`) VALUES 
		(NULL, '".$_POST['srno']."', '".$_POST['sample1']."', '".$_POST['sample2']."');
    ";
    $conn->query($sql);
    header('location:salesbills.php');
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
            $sql3 = "
            INSERT INTO `".$table_name."_bill` (`srno`, `bill_no`, `buyer_name`, `buyer_address`, `amount`, `vat`, `other_charges`, `sell_date`, `creation_date`) VALUES 
            (NULL, '".$_POST['billno']."', '".$_POST['buyersname']."', '".$row['address']."', '".$_POST['amount']."', '".$_POST['vat']."',
             '".$_POST['othercharges']."', '".date('Y-m-d', strtotime($_POST['date']))."', CURRENT_TIMESTAMP);
            ";
            $conn->query($sql3);
            $last_id = $conn->insert_id;
            $sql4 = "UPDATE `".$table_name."_description` SET billno=".$last_id." WHERE billno='".$_POST['billno']."-".$_POST['date']."'";
            $conn->query($sql4);

        }
    }
    $conn->close();
    header('location:salesbills.php');
}

if(isset($_POST['srnopurchase'])){
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO `".$table_name."_bill_paid` (`srno`, `srno".$table_name."_bill`, `paiddate`, `paidamount`, `paiddetails`)
    VALUES (NULL, '".$_POST['srnopurchase']."', '".date('Y-m-d', strtotime($_POST['paiddate']))."', '".$_POST['paidamount']."', '".$_POST['details']."')";
    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header('location:salesbills.php');
}


include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM `".$table_name."_bill` ORDER BY sell_date DESC, bill_no DESC  LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $newbillno = $row['bill_no'] + 1;
    }
}else{
    $newbillno = 1;
}
$conn->close();
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
<div class="container" style="margin-bottom:100px" id="table">
  <h2>Sale Bills<div class="pull-right">
          <!-- Trigger the modal with a button -->
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal" onclick="refresh()">Add New</button>
      </div></h2>
  <table class="table table-bordered" id="example">
    <thead>
    <tr>
        <th>Bill No</th>
        <th>Date</th>
        <th>Buyers Name</th>
        <!--<th>Buyers Address</th>-->
        <th>Amount</th>
        <th>Vat</th>
        <th>Other Charges</th>
        <th>Grand Total</th>
        <th>Details</th>
        <th>Delete</th>
        <th>Edit</th>
        <th>Add<br>Sample</th>
        <th>Print<br>Challan</th>
        <th>Print<br>Invoice</th>
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

    $sql = "SELECT * FROM ".$table_name."_bill";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) { 
            $total = 0;
            $total = (($row['amount'] + $row['other_charges'])*($row['vat']/100)) + ($row['amount'] + $row['other_charges']);
            $total = round($total);
            ?>
            <tr>
                <td><?=$row['bill_no']?></td>
                <td width="80px"><?=date("d-m-Y", strtotime($row['sell_date']))?></td>
                <td><?=$row['buyer_name']?></td>
                <!--<td><?=$row['buyer_address']?></td>-->
                <td><?=$row['amount']?></td>
                <td><?=$row['vat']?></td>
                <td><?=$row['other_charges']?></td>
                <td><?=$total?></td>
                <td align="center"><button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?=$row['srno']?>">
                <i class="fa fa-cog" aria-hidden="true"></i>
                </button></td>
                <td align="center"><a href="salesbills.php?deletebill=<?=$row['srno']?>"><button class="btn btn-default"><i class="fa fa-times" aria-hidden="true" style="color:red"></i></button></a></td>
                <td><button class="btn btn-success" onclick="location.href='editbill.php?billno=<?=$row['srno']?>' ">Edit</button></td>
                <td><button class="btn btn-success" onclick="addsample(<?=$row['srno']?>)">Add</button></td>
                <td><button class="btn btn-success" onclick=" window.open('bill_challan.php?billno=<?=$row['srno']?>', '', 'width=800,height=1000') ">Print</button></td>
                <td><button class="btn btn-success" onclick=" window.open('bill_invoice.php?billno=<?=$row['srno']?>', '', 'width=800,height=1000') ">Print</button></td>
            </tr>
        <?php
        }
    } else {
        //echo "0 results";
    }
    ?>
    </tbody>
  </table>
</div>



<?php
$sql = "SELECT * FROM ".$table_name."_bill";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) { ?>
    <!-- Modal -->
    <div id="myModal<?=$row['srno']?>" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Details For Bill No. <?=$row['bill_no']?></h4>
        </div>
        <div class="modal-body">
            <table class="table">
                <thead>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Rate</th>
                    <th>Weight</th>
                    <th>Total</th>
                    <th>Labour</th>
                </thead>
                <tbody>
                    <?php
                        $sql2="SELECT * FROM ".$table_name."_description WHERE billno=".$row['srno'];
                        $result2 = $conn->query($sql2);
                        
                        $totalqty=0;
                        $totalrate=0;
                        $totalweight=0;
                        $totalprice=0;
                        $totallabour=0;

                        if ($result2->num_rows > 0) {
                            // output data of each row
                            while($row2 = $result2->fetch_assoc()) { 
                                $totalqty=$totalqty + $row2['quantity'];
                                $totalrate=$totalrate + $row2['item_rate'];
                                $totalweight=$totalweight + $row2['weight'];
                                $totalprice=$totalprice + ($row2['quantity']*$row2['item_rate']);
                                $totallabour= $totallabour + ($row2['labour']*$row2['quantity']);
                                
                                ?> 
                            <tr>
                                <td><?=$row2['item_name']?></td>
                                <td><?=$row2['quantity']?></td>
                                <td><?=$row2['item_rate']?></td>
                                <td><?=$row2['weight']?></td>
                                <td><?=$row2['quantity']*$row2['item_rate']?></td>
                                <td><?=$row2['labour']*$row2['quantity']?></td>
                            </tr>
                            <?php
                            }
                        }

                        if($totalprice > 0){ ?>
                            <tr style="font-weight:bold">
                                <td>Total Amount</td>
                                <td><?=$totalqty?></td>
                                <td></td>
                                <td><?=$totalweight?></td>
                                <td><?=$totalprice?></td>
                                <td><?=$totallabour?></td>
                            </tr>
                            <tr>
								<td colspan="2"><b>VAT : <?=round(($row['vat']/100) * ($totalprice + $row['other_charges']))?></b></td>
								<td colspan="2"><b>Other Charges : <?=$row['other_charges']?></b></td>
								<td colspan="2"><b>Grand Total : <?=round(round(($row['vat']/100) * ($totalprice + $row['other_charges'])) + $totalprice) + $row['other_charges']?></b></td>
                            </tr>
                        <?php
                        }
                    ?>
                </tbody>
            </table>
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
$conn->close();
?>



<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Sales Details</h4>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="col-md-12">
                        <div class="form-group col-md-3">
                            <label for="itemname">Bill No:</label>
                            <input type="text" class="form-control" id="billno" name="billno" value="<?=$newbillno?>" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="itemprice">Date:</label>
                            <input type="text" class="form-control datepicker" id="date" name="date" value="<?=date('d-m-Y')?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="buyersname">Buyers Name:</label>
                            <select class="form-control" id="buyersname" name="buyersname" required>
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
                    </div>
                    <hr style="margin: 5px">
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
                    <div style="margin-bottom: 250px" id="description"><p style="padding-top: 10px; text-align: center; color: red">No Details added yet</p></div>
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
                                <input type="number" onchange="calculate()" oninput="calculate()" class="form-control" min="0" value="0" id="othercharges" step="any" readonly name="othercharges" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vat">VAT in %age:</label>
                                <input type="number" onchange="calculate()" oninput="calculate()" class="form-control" min="0" value="0" id="vat" step="any" readonly name="vat" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="totalamount">Total Amount:</label>
                                <input type="number" class="form-control" id="totalamount" step="any" readonly name="totalamount" required>
                            </div>
                        </div>
                    </div>
                    <hr>
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

<!-- Modal -->
    <div class="modal fade" id="addsample" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Sample</h4>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="srno" id="srno" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="dates">Sample 1:</label>
                            <input type="text" class="form-control" name="sample1" required>
                        </div>
                        <div class="form-group">
                            <label for="dates">Sample 2:</label>
                            <input type="text" class="form-control" name="sample2">
                        </div>
                        <button type="submit" class="btn btn-default">Save</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

<!-- Modal Finish-->


<script>
    $( function() {
        $( "#date" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );
    
    
    function addsample(i){
	$('#srno').val(i);
	$('#addsample').modal('show');
	}

    function submitdescription(){
        var itemname = document.getElementById('itemname').value;
        var quantity = document.getElementById('quantity').value;
        var weight = document.getElementById('weight').value;
        var billno = document.getElementById('billno').value;
        var sell_date = document.getElementById('date').value;

        var data = {
            itemname : itemname,
            quantity : quantity,
            weight : weight,
            billno : billno + '-' +sell_date
        };
        $.post( "display_description_<?=$table_name?>.php", data)
            .done(function( data1 ) {
                refresh();
            });

    }

    function refresh(){
        var billno = document.getElementById('billno').value;
        var sell_date = document.getElementById('date').value;

        var data = {
            billno : billno+'-'+sell_date,
            table : "yes"
        };

        $.post( "display_description_<?=$table_name?>.php", data)
            .done(function( data1 ) {
                $('#description').html(data1);
                totalamount();
            });
    }

    function totalamount(){
        var billno = document.getElementById('billno').value;
        var sell_date = document.getElementById('date').value;
        var data = {
            billno : billno+'-'+sell_date,
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
    function openmodal(a){
        document.getElementById('srnopurchase').value = a;
        $('#myModalpurchase').modal('show');
    }
</script>
</body>
</html>

