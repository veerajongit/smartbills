<?php
include "session.php";
include "db.php";
if(!isset($_REQUEST['billno'])){
    echo "Something went wrong please try again.";
    exit;
}
function numtoword($number){
   $no = round($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  return $result . "Rupees  ";
	}
$sql = "SELECT * FROM company_details WHERE companyname='".$_SESSION['work']."' AND isdelete IS NULL";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $companyname = $row['fullname'];
        $companyaddr = $row['companyaddress'];
        $vat = $row['vat'];
        $cst = $row['cst'];
    }
} else {
    echo "Invalid Session. Please logout and login to print bill";
    exit;
}

$sql = "SELECT * FROM ".$_SESSION['work']."_bill WHERE srno=".$_REQUEST['billno'];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $billno = $row['srno'];
        $date = date('d-m-Y', strtotime($row['sell_date']));
        $buyername = $row['buyer_name'];
        $buyeraddress = $row['buyer_address'];
        $amount = $row['amount'];
        $amountvat = $row['vat'];
        $othercharges = $row['other_charges'];
        $total = ( round(($row['amount'] + $row['other_charges'])*($row['vat']/100)) ) + ($row['amount'] + $row['other_charges']);
        $ctotal = round($total);
    }
}else{
    echo "Something went wrong. Please go back and try again.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SmartBills - Past Bills</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href='css/opensans.css' rel='stylesheet' type='text/css'>
    <style type="text/css">
		@media print { body { -webkit-print-color-adjust: exact; } }
        body{
            font-family: 'Open Sans';
        }
    </style>
</head>
<body>

<div style="height: 25cm">
    <div class="text-center">
        <h4>TAX INVOICE</h4>
        <h1><b><?=$companyname?></b></h1>
        <p><?=explode("<br>", $companyaddr)[0].explode("<br>", $companyaddr)[1].explode("<br>", $companyaddr)[2]?></p>
    </div>
    <hr>
    <table class="table table-bordered">
        <tr>
            <td width="450px"><b>M/s : </b><?=$buyername?></td>
            <td><b>Invoice No :</b> <?=$_REQUEST['billno']?></td>
            <td><b>Date :</b> <?=$date?></td>
        </tr>
        <tr>
            <td><?=$buyeraddress?></td>
            <td><b>Challan No :</b> <?=$_REQUEST['billno']?></td>
            <td><b>Date :</b> <?=$date?></td>
        </tr>
    </table>
    <table class="table table-bordered">
        <tr style="font-weight: bold; text-align: center">
            <td>Sr No</td>
            <td>PARTICULARS</td>
            <td>Nos</td>
            <td>Rate</td>
            <td>Total Amount</td>
        </tr>
        <?php
        $i = 0;
        $sql2="SELECT * FROM ".$table_name."_description WHERE billno=".$billno.' ORDER BY item_name';
        $result2 = $conn->query($sql2);

        $totalqty=0;
        $totalrate=0;
        $totalweight=0;
        $totalprice=0;
        $totallabour=0;

        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
                $i = $i +1;
                $totalqty=$totalqty + $row2['quantity'];
                $totalrate=$totalrate + $row2['item_rate'];
                $totalweight=$totalweight + $row2['weight'];
                $totalprice=$totalprice + ($row2['quantity']*$row2['item_rate']);
                $totallabour= $totallabour + $row2['labour'];

                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$row2['item_name']?></td>
                    <td><?=$row2['quantity']?></td>
                    <!--<td><?=$row2['weight']?></td>-->
                    <td><?=number_format((float)$row2['item_rate'],2,'.','')?></td>
                    <td><?=number_format((float)($row2['quantity']*$row2['item_rate']),2, '.','')?></td>
                    <!--<td><?=$row2['labour']?></td>-->
                </tr>
                <?php
            }
        }
        for(; $i<=9; $i++){ ?>
            <tr height="35px">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <!--<td></td>-->
            </tr>
            <?php
        }

        ?>

        <tr height="35px">
            <td rowspan="2" colspan="2"> Grand Total (in words) : <?=numtoword($ctotal)?></td>
            <td>Total</td>
            <td></td>
            <td><?=number_format((float)$amount, 2, '.','')?></td>
        </tr>
        <tr height="35px">
            <td>P & F</td>
            <td></td>
            <td><?=number_format((float)$othercharges,2,'.','')?></td>
        </tr>
        <tr height="35px">
            <td rowspan="2" colspan="2"><?=$vat?><br><?=$cst?></td>
            <td>VAT <?=$amountvat?>%</td>
            <td></td>
            <td><?=number_format((float)round(($amount+$othercharges)*($amountvat/100)),2,'.','')?></td>
        </tr>
        <tr height="35px">
            <td>Grand Total:</td>
            <td></td>
            <td><?=number_format((float)$ctotal,2,'.','')?></td>
        </tr>
    </table>
    <div class="row" style="padding-left: 10px; padding-right: 10px">
        <div class="col-xs-10">
            <p>I/We hereby certify that my/our registration certificate under the Mahrashtra Value
            Added Tax Act 2002 is in force on the date on which the sale of the goods specified in this tax
            tax invoice is made by me/us and that the transaction of sale covered by this tax invoice
            has been effected by me/us it shall be accounted or in the turnover of sales while filling
            of return and the due tax, if any, payable on the sale has been paid or shall be paid*</p>
        </div>
    </div>
    <div class="row" style="padding-left: 10px; padding-right: 10px; font-weight: bold; padding-top: 15px">
        <div class="pull-right" style="color: darkgreen">For Precision Press Components</div>
    </div>
    
</div>
<script type="text/javascript">
    window.print();
    setTimeout(function () { window.close(); }, 100);
</script>
</body>
</html>
