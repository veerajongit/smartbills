<?php
include "session.php";
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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
                                        $total = (($row3['amount'] + $row3['other_charges'])*($row3['vat']/100)) + ($row3['amount'] + $row3['other_charges']);
                                        $total = round($total);
                                        ?>
                                        <tr>
                                            <td><?=$row3['bill_no']?></td>
                                            <td><?=date('d-m-Y', strtotime($row3['sell_date']))?></td>
                                            <td><?=$total?></td>
                                            <td>
                                                <?php
                                                $sql4 = "SELECT COALESCE(SUM(paidamount), 0) FROM ".$table_name."_bill_paid WHERE srno".$table_name."_bill=" . $row3['srno'];
                                                $result4 = $conn->query($sql4);
                                                $paid_total = 0;
                                                while ($row4 = $result4->fetch_assoc()) {
                                                    echo $paid_total = $row4['COALESCE(SUM(paidamount), 0)'];
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
                            <div class="col-md-3"><?=$row1['buyer_name']?> Company Report</div>
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
