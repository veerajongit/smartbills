<?php
if(isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['address'])){
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO `worker` (`srno`, `worker_name`, `phone`, `address`, `salary`) VALUES (NULL, '".$_POST['name']."', '".$_POST['phone']."', '".$_POST['address']."', '".$_POST['salary']."');";
if(isset($_POST['srno'])){
    $sql = "DELETE FROM  worker WHERE srno=".$_POST['srno'];
    $conn->query($sql);
    $sql = "INSERT INTO `worker` (`srno`, `worker_name`, `phone`, `address`, `salary`) VALUES (".$_POST['srno'].", '".$_POST['name']."', '".$_POST['phone']."', '".$_POST['address']."', '".$_POST['salary']."');";
}
if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
}


if(isset($_POST['workerid']) && isset($_POST['dateloan']) && isset($_POST['loanamount'])){
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "INSERT INTO `workerloan` (`srno`, `date`, `amount`, `datetime`, `workersrno`) VALUES (NULL, '".date('Y-m-d', strtotime($_POST['dateloan']))."', '".$_POST['loanamount']."', CURRENT_TIMESTAMP, '".$_POST['workerid']."');";
    if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}


if(isset($_POST['workerid']) && isset($_POST['salarypaiddate']) && isset($_POST['daysfilled']) && isset($_POST['totalsal']) && isset($_POST['perdaysal'])){
    $sql = "
    INSERT INTO `salarygiven` (`srno`, `date`, `perdaysal`, `nodaysfilled`, `totalsalary`, `datetime`, `workerno`) VALUES 
    (NULL, '".date('Y-m-d', strtotime($_POST['salarypaiddate']))."', '".$_POST['perdaysal']."', '".$_POST['daysfilled']."', '".$_POST['totalsal']."', CURRENT_TIMESTAMP, 
    '".$_POST['workerid']."');
    ";
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    if ($conn->query($sql) === TRUE) {
        //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

if(isset($_POST['workersrno']) && isset($_POST['paiddate']) && isset($_POST['paidamount'])){
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "INSERT INTO `workerloanpaid` (`srno`, `date`, `amount`, `datetime`, `workersrno`) VALUES (NULL, '".date('Y-m-d', strtotime($_POST['paiddate']))."', '".$_POST['paidamount']."', CURRENT_TIMESTAMP, '".$_POST['workersrno']."');";
    if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}


if(isset($_REQUEST['delete'])){
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "UPDATE worker SET is_delete = 1 WHERE srno=".$_REQUEST['delete'];
    if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
    header('location:workers.php');
    } else {
        //echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SmartBills - Workers</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
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
                "pageLength": 50
            });
        } );
    </script>
</head>
<body>
<?php include "navbar.php"; ?>
<div class="container">
    <h1>Workers<div class="pull-right">
    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add New</button>
    </div></h1>
    
    <table class="table table-bordered" id="example">
    <thead>
    <tr>
        <th>Name</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Salary(per day)</th>
        <th>Loan Taken</th>
        <th>Loan Paid</th>
        <th>Loan Bal</th>
        <th>Pay Salary</th>
        <th>Edit</th>
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

    $sql = "SELECT * FROM worker WHERE is_delete IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?=$row['worker_name']?></td>
                <td><?=$row['phone']?></td>
                <td><?=$row['address']?></td>
                <td><?=$row['salary']?></td>
                <td align="center">
                    <?php
                    $loanamount = 0;
                    $sql = "SELECT SUM(amount) FROM workerloan WHERE workersrno=".$row['srno'];
                    $result2 = $conn->query($sql);

                    if ($result2->num_rows > 0) {
                        // output data of each row
                        while($row2 = $result2->fetch_assoc()) {
                            echo $loanamount = $row2['SUM(amount)'];
                        }
                    }
                    ?>
                    <button class="btn btn-sm btn-info" onclick="addloan(<?=$row['srno']?>)">Add Loan</button>
                </td>
                <td align="center">
                <?php
                $loanamountpaid = 0;
                    $sql = "SELECT SUM(amount) FROM workerloanpaid WHERE workersrno=".$row['srno'];
                    $result2 = $conn->query($sql);

                    if ($result2->num_rows > 0) {
                        // output data of each row
                        while($row2 = $result2->fetch_assoc()) {
                            echo $loanamountpaid = $row2['SUM(amount)'];
                        }
                    }
                    ?>
                <button class="btn btn-sm btn-info" onclick="payoff(<?=$row['srno']?>)">Pay off</button>
                </td>
                <td align="center"><?php echo $loanamount - $loanamountpaid; $loanamount = 0; $loanamountpaid = 0;?></td>
                <td align="center">
					<?php
					$sqlsal = "SELECT SUM(totalsalary) FROM salarygiven WHERE workerno=".$row['srno'];
					$result2 = $conn->query($sqlsal);

                    if ($result2->num_rows > 0) {
                        // output data of each row
                        while($row2 = $result2->fetch_assoc()) {
                            if($row2['SUM(totalsalary)'] == ''){
								echo 0;
							}
							echo $row2['SUM(totalsalary)'].'<br>';
                        }
                    }
					?>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#paysalary<?=$row['srno']?>">
                        <i class="fa fa-cog" aria-hidden="true"></button></td>
                <td align="center">
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?=$row['srno']?>">
                <i class="fa fa-cog" aria-hidden="true"></button></td>
                <td align="center"><button onclick="location.href='workers.php?delete=<?=$row['srno']?>'" class="btn btn-default"><i class="fa fa-times" style="color:red" aria-hidden="true"></button></td>
            </tr>
        <?php
        }
    }
    ?>
    </tbody>
  </table>
  
</div>

<!-- Modal -->
<div id="myModalloantaken" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Loan Taken</h4>
      </div>
      <div class="modal-body">
        <form method="post">
            <input type="text" required id="workerid" name="workerid" class="hidden">
            <div class="form-group">
                <label for="dateloan">Date of loan:</label>
                <input class="form-control" type="text" id="dateloan" name="dateloan" value="<?=date("d-m-Y")?>" required>
            </div>
            <div class="form-group">
                <label for="loanamount">Loan amount:</label>
                <input class="form-control" type="number" id="loanamount" name="loanamount" required>
            </div>
            <button type="submit" class="btn btn-info">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div id="myModalloanpaidoff" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Loan Paid Off</h4>
      </div>
      <div class="modal-body">
        <form method="post">
            <input type="text" required id="workersrno" name="workersrno" class="hidden">
            <div class="form-group">
                <label for="dateloan">Date of loan:</label>
                <input class="form-control" type="text" id="paiddate" name="paiddate" value="<?=date("d-m-Y")?>" required>
            </div>
            <div class="form-group">
                <label for="loanamount">Loan amount:</label>
                <input class="form-control" type="number" id="paidamount" name="paidamount" required>
            </div>
            <button type="submit" class="btn btn-info">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<?php
    $sql = "SELECT * FROM worker WHERE is_delete IS NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            ?>
<!-- Modal -->
<div id="myModal<?=$row['srno']?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Workers</h4>
      </div>
      <div class="modal-body">
        <form method="post">
            <input type="text" class="form-control hidden" id="srno" name="srno" value="<?=$row['srno']?>" required>
            <div class="form-group">
                <label for="itemname">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?=$row['worker_name']?>" required>
            </div>
            <div class="form-group">
                <label for="itemprice">Phone:</label>
                <input type="number" class="form-control" id="phone" step="any" name="phone" value="<?=$row['phone']?>" required>
            </div>
            <div class="form-group">
                <label for="itemlabour">Address:</label>
                <input type="text" class="form-control" id="address" step="any" name="address" value="<?=$row['address']?>" required>
            </div>
            <div class="form-group">
                <label for="itemlabour">Salary(per day):</label>
                <input type="text" class="form-control" id="salary" step="any" name="salary" value="<?=$row['salary']?>" required>
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

<?php
$sql = "SELECT * FROM worker WHERE is_delete IS NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        ?>
        <!-- Modal -->
        <div id="paysalary<?=$row['srno']?>" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Workers</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="text" class="form-control hidden" id="workerid" name="workerid" value="<?=$row['srno']?>" required>
                            <div class="form-group">
                                <label for="dateloan">Date of Salary:</label>
                                <input class="form-control datepicker" type="text" id="salarypaiddate" name="salarypaiddate" value="<?=date("d-m-Y")?>" required>
                            </div>
                            <div class="form-group">
                                <label for="perdaysal">Per Day Salary:</label>
                                <input type="text" class="form-control" id="perdaysal" name="perdaysal" value="<?=$row['salary']?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="daysfilled">No of Days filled:</label>
                                <input type="number" class="form-control" id="daysfilled<?=$row['srno']?>" step="any" name="daysfilled" onchange="function<?=$row['srno']?>(<?=$row['salary']?>)" oninput="function<?=$row['srno']?>(<?=$row['salary']?>)" value="1" min="1" required>
                            </div>
                            <div class="form-group">
                                <label for="totalsal">Total Salary:</label>
                                <input type="number" class="form-control" id="totalsal<?=$row['srno']?>" step="any" name="totalsal" readonly value="<?=$row['salary']?>" required>
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

        <script>
            function function<?=$row['srno']?>(a){
                document.getElementById('totalsal<?=$row['srno']?>').value = document.getElementById('daysfilled<?=$row['srno']?>').value * a;
            }
        </script>
        <?php
    }
}
$conn->close();
?>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add new Address</h4>
      </div>
      <div class="modal-body">
        <form method="post">
            <div class="form-group">
                <label for="itemname">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="itemprice">Phone:</label>
                <input type="number" class="form-control" id="phone" step="any" name="phone" required>
            </div>
            <div class="form-group">
                <label for="itemlabour">Address:</label>
                <input type="text" class="form-control" id="address" step="any" name="address" required>
            </div>
            <div class="form-group">
                <label for="itemlabour">Salary(per day):</label>
                <input type="text" class="form-control" id="salary" step="any" name="salary" required>
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


<script>
    $( function() {
        $( "#dateloan" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );

    $( function() {
        $( "#paiddate" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );

    $( function() {
        $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );

    function payoff(srno){
        $('#workersrno').val(srno);
        $('#myModalloanpaidoff').modal('show');
    }

    function addloan(srno){
        $('#workerid').val(srno);
        $('#myModalloantaken').modal('show');
    }
</script>
</body>
</html>
