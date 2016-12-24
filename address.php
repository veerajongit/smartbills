
<?php 
if(isset($_POST['company']) && isset($_POST['address'])){
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO `address` (`srno`, `companyname`, `address`, `creationdate`) VALUES (NULL, '".$_POST['company']."', '".$_POST['address']."', CURRENT_TIMESTAMP);";
if(isset($_POST['srno'])){
    $sql = "DELETE FROM  address WHERE srno=".$_POST['srno'];
    $conn->query($sql);
    $sql = "INSERT INTO `address` (`srno`, `companyname`, `address`, `creationdate`) VALUES (".$_POST['srno'].", '".$_POST['company']."', '".$_POST['address']."', CURRENT_TIMESTAMP);";
}
if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SmartBills - Address</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
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
    <h1>Company Details<div class="pull-right">
    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add New</button>
    </div></h1>
    
    <table class="table table-bordered" id="example">
    <thead>
    <tr>
        <th>Company Name</th>
        <th>Address</th>
        <th>Edit</th>
    </tr>
    </thead>
    <tbody>
    <?php
    include "db.php";
    $sql = "SELECT * FROM `bill`";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT * FROM address";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td width="375px"><?=$row['companyname']?></td>
                <td><?=$row['address']?></td>
                <td align="center"><button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?=$row['srno']?>">
                <i class="fa fa-cog" aria-hidden="true"></button></td>
            </tr>
        <?php
        }
    }
    ?>
    </tbody>
  </table>
  
</div>

<?php
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
        <h4 class="modal-title">Edit Address</h4>
      </div>
      <div class="modal-body">
                <form method="post">
                <input type="text" class="form-control hidden" id="srno" name="srno" value="<?=$row['srno']?>" required>
            <div class="form-group">
                <label for="company">Company Name:</label>
                <input type="text" class="form-control" id="company" name="company" value="<?=$row['companyname']?>" required>
            </div>
            <div class="form-group">
                <label for="address">Company Address:</label>
                <input type="text" class="form-control" id="address" value="<?=$row['address']?>" name="address" required>
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
                <label for="company">Company Name:</label>
                <input type="text" class="form-control" id="company" name="company" required>
            </div>
            <div class="form-group">
                <label for="address">Company Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
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
</body>
</html>
