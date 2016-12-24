<?php
include "session.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SmartBills - Sales Reports</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <link href='css/opensans.css' rel='stylesheet' type='text/css'>
    <style>
        body{
            font-family: 'Open Sans';
        }
    </style>
</head>
<body>
<?php include "navbar.php"; ?>

<?php
$purchase_total = 0;
$purchase_paid_total = 0;
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($_REQUEST['deletepaid']){
	$sql = "DELETE FROM `smartbills`.`fine_company_paid` WHERE `fine_company_paid`.`srno` = ".$_REQUEST['deletepaid'];
	$conn->query($sql);
	header('location:salereport.php?party_name='.$_REQUEST['party_name']);
}

if(isset($_POST['cname']) && isset($_POST['dated']) && isset($_POST['amount']) && isset($_POST['remarks'])){
    $sql = "
    INSERT INTO `smartbills`.`".$table_name."_company_paid` (`srno`, `company_name`, `paid_date`, `amount`, `remarks`) VALUES 
    (NULL, '".$_POST['cname']."', '".date('Y-m-d', strtotime($_POST['dated']))."', '".$_POST['amount']."', '".$_POST['remarks']."');
    ";
    $conn->query($sql);
    
}

$sql1 = "SELECT DISTINCT buyer_name FROM `".$table_name."_bill` ORDER BY buyer_name";
$result1 = $conn->query($sql1); ?>

<div class="container">
    <b>Company Names</b><br>
    <?php
    if(!isset($_REQUEST['party_name'])){
        echo "<div class='col-md-2'><a href='purchasereport.php' style='pointer-events: none; font-weight:bold; color:darkblue'>All</a></div>";
    }else {
        echo "<div class='col-md-2'><a href='salereport.php'>All</a></div>";
    }
    while ($row1 = $result1->fetch_assoc()) {
        if($_REQUEST['party_name'] == $row1['buyer_name']){
            echo "<div class='col-md-2'><a href='salereport.php?party_name=" . $row1['buyer_name'] . "' style='pointer-events: none; font-weight:bold; color:darkblue'>" . $row1['buyer_name'] . "</a></div>";
        }else {
            echo "<div class='col-md-2'><a href='salereport.php?party_name=" . $row1['buyer_name'] . "'>" . $row1['buyer_name'] . "</a></div>";
        }
    } 
?>    
</div>
<?php
    if(isset($_REQUEST['party_name'])){ ?>
	<div class='container'>
		<div class="pull-right">
			<a class="btn btn-primary" target="_blank" href="salesbillexcel.php?party_name=<?=$_REQUEST['party_name']?>">Export Excel</a>
		</div>
	</div>
	<?php	
	}
    ?>
<hr>
<?php
if(isset($_REQUEST['party_name'])) {
    $sql1 = "SELECT DISTINCT buyer_name FROM `".$table_name."_bill` WHERE buyer_name='".$_REQUEST['party_name']."' ";
}
$result1 = $conn->query($sql1);

if ($result1->num_rows > 0) {
    // output data of each row
    while ($row1 = $result1->fetch_assoc()) {
        $company_total = 0;
        $company_paid_total= 0;
        ?>
        
        <?php if(isset($_REQUEST['party_name'])){ ?>
		<div class="container" style="margin-bottom: 20px">	
			<div class="pull-right">
				<!-- Trigger the modal with a button -->
				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#addpayment">Add Payment</button>
			</div>
		</div>
			<!-- Modal -->
			<div class="modal fade" id="addpayment" role="dialog">
				<div class="modal-dialog">

					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Add Payment</h4>
						</div>
						<div class="modal-body">
							<form method="post" action="salereport.php?party_name=<?=$_REQUEST['party_name']?>">
								<div class="form-group">
									<label for="cname">Company Name:</label>
									<input type="text" class="form-control" name="cname" value="<?=$_REQUEST['party_name']?>" required readonly>
								</div>
								<div class="form-group">
									<label for="dates">Date:</label>
									<input type="text" class="form-control" id="datepicker" name="dated" required>
								</div>
								<div class="form-group">
									<label for="amount">Amount:</label>
									<input min="0" type="number" class="form-control" name="amount" required>
								</div>
								<div class="form-group">
									<label for="remarks">Remarks:</label>
									<input type="text" class="form-control" name="remarks">
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
		<?php } ?>
        <div class="container">
            <table class="table table-hover">
                <?php
                echo "<tr><th>".$row1['buyer_name']."</th></tr>";
                $sql2 = "SELECT * FROM `".$table_name."_bill` WHERE buyer_name='".$row1['buyer_name']."' ORDER BY sell_date";
                $result2 = $conn->query($sql2);
                while ($row2 = $result2->fetch_assoc()) {
                    $start_date = $row2['sell_date'];
                    break;
                }
                $sql2 = "SELECT * FROM `".$table_name."_bill` WHERE buyer_name='".$row1['buyer_name']."' ORDER BY sell_date DESC";
                $result2 = $conn->query($sql2);
                while ($row2 = $result2->fetch_assoc()) {
                    $end_date = $row2['sell_date'];
                    break;
                }

                $begin = new DateTime($start_date);
                $end = new DateTime($end_date);

                while ($begin <= $end) {
                    $startday = $begin->format('Y-m')."-01";
                    $endday = date("Y-m-t", strtotime($startday));

                    $sql3 = "SELECT * FROM `".$table_name."_bill` WHERE buyer_name='".$row1['buyer_name']."' AND sell_date >= '$startday' AND sell_date <= '$endday' ";
                    $result3 = $conn->query($sql3);
                    if($result3->num_rows > 0) {
                        echo "<tr><th>";
                        echo "<div class=''>";
                        echo "<div class='pull-left'>Month : ".$begin->format('M')."</div><div class='pull-right'>Year : ".$begin->format('Y')."</div>";
                        echo "</div>";
                        echo "</th></tr>"; ?>
                        <tr><td>
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Bill No</th>
                                        <th>Bill date</th>
                                        <th>Total Bill Amount</th>
                                        <th>Payment Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $monthly_total = 0;
                                    $monthly_paid_total = 0;
                                    while ($row3 = $result3->fetch_assoc()) {
                                        $total = 0;
                                        $total = (($row3['amount'] + $row3['other_charges'])*($row3['vat']/100)) + ($row3['amount'] + $row3['other_charges']);
                                        $total = round($total);
                                        ?>
                                        <tr>
                                            <td><?=$row3['bill_no']?></td>
                                            <td><?=date('d-m-Y', strtotime($row3['sell_date']))?></td>
                                            <td><?=$total?></td>
                                            <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?=$row3['srno']?>">
                                                    <i class="fa fa-cog" aria-hidden="true"></i></button></td>
                                        </tr>
                                        <?php
                                        $monthly_total = $monthly_total + $total;
                                        $monthly_paid_total = $monthly_paid_total + $paid_total;
                                    } ?>
                                    
                                    <?php
                                    if(isset($_REQUEST['party_name'])){
										$sqlpaid = "SELECT * FROM `".$table_name."_company_paid` WHERE company_name='".$row1['buyer_name']."' AND paid_date >= '$startday' AND paid_date <= '$endday' ";
										$resultpaid = $conn->query($sqlpaid);
										if($resultpaid->num_rows > 0) {
											while ($rowpaid = $resultpaid->fetch_assoc()) { ?>
												<tr>
													<td></td>
													<td><?=date('d-m-Y', strtotime($rowpaid['paid_date']))?></td>
													<td><?=$rowpaid['amount']?></td>
													<td><a href="salereport.php?deletepaid=<?=$rowpaid['srno']?>&party_name=<?=$_REQUEST['party_name']?>"><button class="btn btn-danger">Delete</button></a></td>
												</tr>
											<?php
												$monthly_total = $monthly_total - $rowpaid['amount'];
												$monthly_paid_total = $monthly_paid_total - $rowpaid['amount'];
											}
										}
									}
                                    ?>
                                    
                                    <tr>
                                        <th></th>
                                        <th>Total</th>
                                        <th><?=$monthly_total?></th>
                                        <th></th>
                                    </tr>
                                    </tbody>
                                </table>
                            </td></tr>
                        <?php
                        $company_total = $company_total + $monthly_total;
                        $company_paid_total= $company_paid_total + $monthly_paid_total;
                    }


                    $begin->modify('first day of next month');
                } ?>
                <tr>
                    <td style="text-align: center; background-color: greenyellow">
                        <b>
                            <div class="col-md-3"><?=$row1['buyer_name']?> Company Report</div>
                            <div class="col-md-3">Total : <?=$company_total?></div>
                        </b>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        $purchase_total = $purchase_total + $company_total;
        $purchase_paid_total = $purchase_paid_total + $company_paid_total;
        $company_total = 0;
        $company_paid_total= 0;
    } ?>
    <?php
}
$conn->close();
?>
<div class="container" style="text-align: center; padding: 20px">
    <table class="table table-hover" style="background-color: greenyellow"><tr>
            <td>
                <b>Total : <?=$purchase_total?></b>
            </td>
</tr></table>
</div>

<script>
    $( function() {
        $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );
</script>
</body>
</html>
