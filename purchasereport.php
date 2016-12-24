<?php
include "session.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SmartBills - Purchase Reports</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
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
$sql1 = "SELECT DISTINCT party_name FROM `purchase_".$table_name."` ORDER BY party_name";
$result1 = $conn->query($sql1); ?>

<div class="container">
    <b>Company Names</b><br>
<?php
if(!isset($_REQUEST['party_name'])){
    echo "<div class='col-md-2'><a href='purchasereport.php' style='pointer-events: none; font-weight:bold; color:darkblue'>All</a></div>";
}else {
    echo "<div class='col-md-2'><a href='purchasereport.php'>All</a></div>";
}

while ($row1 = $result1->fetch_assoc()) {
    if($_REQUEST['party_name'] == $row1['party_name']){
        echo "<div class='col-md-2'><a href='purchasereport.php?party_name=" . $row1['party_name'] . "' style='pointer-events: none; font-weight:bold; color:darkblue'>" . $row1['party_name'] . "</a></div>";
    }else {
        echo "<div class='col-md-2'><a href='purchasereport.php?party_name=" . $row1['party_name'] . "'>" . $row1['party_name'] . "</a></div>";
    }
} ?>
</div>
<hr>
    <?php
if(isset($_REQUEST['party_name'])) {
    $sql1 = "SELECT DISTINCT party_name FROM `purchase_" . $table_name . "` WHERE party_name='".$_REQUEST['party_name']."' ";
}

$result1 = $conn->query($sql1);
if ($result1->num_rows > 0) {
    // output data of each row
    while ($row1 = $result1->fetch_assoc()) {
        $company_total = 0;
        $company_paid_total= 0;
        ?>
<div class="container">
    <table class="table table-hover">
        <?php
        echo "<tr><th>".$row1['party_name']."</th></tr>";
        $sql2 = "SELECT * FROM `purchase_".$table_name."` WHERE party_name='".$row1['party_name']."' ORDER BY pur_date";
        $result2 = $conn->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
            $start_date = $row2['pur_date'];
            break;
        }
        $sql2 = "SELECT * FROM `purchase_".$table_name."` WHERE party_name='".$row1['party_name']."' ORDER BY pur_date DESC";
        $result2 = $conn->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
            $end_date = $row2['pur_date'];
            break;
        }

        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);

        while ($begin <= $end) {
            $startday = $begin->format('Y-m')."-01";
            $endday = date("Y-m-t", strtotime($startday));

            $sql3 = "SELECT * FROM `purchase_".$table_name."` WHERE party_name='".$row1['party_name']."' AND pur_date >= '$startday' AND pur_date <= '$endday' ";
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
                    <th>Total Bill Paid</th>
                    <th>Unpaid Amount</th>
                    <th>Payment Details</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $monthly_total = 0;
                $monthly_paid_total = 0;
                while ($row3 = $result3->fetch_assoc()) {
                    $total = 0;
                    $total = round((float)$row3['total'] + (float)$row3['tax'] + (float)$row3['othercharges']);
                    ?>
                    <tr>
                        <td><?=$row3['bill_no']?></td>
                        <td><?=date('d-m-Y', strtotime($row3['pur_date']))?></td>
                        <td><?=$total?></td>
                        <td>
                            <?php
                            $sql4 = "SELECT COALESCE(SUM(paid_amount), 0) FROM purchase_".$table_name."_paid WHERE srnoofpurchase_".$table_name."=" . $row3['srno'];
                            $result4 = $conn->query($sql4);
                            $paid_total = 0;
                            while ($row4 = $result4->fetch_assoc()) {
                                echo $paid_total = $row4['COALESCE(SUM(paid_amount), 0)'];
                            }
                            ?>
                        </td>
                        <td>
                            <?=$total-$paid_total?>
                        </td>
                        <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?=$row3['srno']?>">
                                <i class="fa fa-cog" aria-hidden="true"></i>
                    </tr>
                <?php
                    $monthly_total = $monthly_total + $total;
                    $monthly_paid_total = $monthly_paid_total + $paid_total;
                } ?>
                <tr>
                    <th></th>
                    <th>Total</th>
                    <th><?=$monthly_total?></th>
                    <th><?=$monthly_paid_total?></th>
                    <th><?=$monthly_total-$monthly_paid_total?></th>
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
                    <div class="col-md-3"><?=$row1['party_name']?> Company Report</div>
                    <div class="col-md-3">Total : <?=$company_total?></div>
                    <div class="col-md-3">Paid : <?=$company_paid_total?></div>
                    <div class="col-md-3">Bal : <?=$company_total - $company_paid_total?></div>
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
    <td>
        <b>Paid : <?=$purchase_paid_total?></b>
    </td>
    <td>
        <b>Balance : <?=$purchase_total-$purchase_paid_total?></b>
    </td></tr></table>
</div>


<?php
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql1 = "SELECT * FROM `purchase_".$table_name."`";
$result1 = $conn->query($sql1);
while ($row1 = $result1->fetch_assoc()) { ?>
    <!-- Modal -->
    <div id="myModal<?=$row1['srno']?>" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Details</h4>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Paid Date</th>
                                <th>Paid Amount</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql2 = "SELECT * FROM `purchase_".$table_name."_paid` WHERE srnoofpurchase_".$table_name." = ".$row1['srno'];
                        $result2 = $conn->query($sql2);
                        while ($row2 = $result2->fetch_assoc()) { ?>
                            <tr>
                                <td><?=date('d-m-Y', strtotime($row2['paid_date']))?></td>
                                <td><?=$row2['paid_amount']?></td>
                                <td><?=$row2['details']?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<?php
}
?>
</body>
</html>
