
<?php 
if(isset($_POST['itemname']) && isset($_POST['itemprice']) && isset($_POST['itemlabour'])){
include "db.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO `item_list` (`srno`, `item_name`, `item_price`, `item_labour`) VALUES (NULL, '".$_POST['itemname']."', '".$_POST['itemprice']."', '".$_POST['itemlabour']."');";
if(isset($_POST['itemno'])){
    $sql = "DELETE FROM  item_list WHERE srno=".$_POST['itemno'];
    $conn->query($sql);
    $sql = "INSERT INTO `item_list` (`srno`, `item_name`, `item_price`, `item_labour`) VALUES (".$_POST['itemno'].", '".$_POST['itemname']."', '".$_POST['itemprice']."', '".$_POST['itemlabour']."');";
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
    <title>SmartBills - Item List</title>
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
    <h1>Item List<div class="pull-right">
    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">Add New</button>
    </div></h1>
    
    <table class="table table-bordered" id="example">
    <thead>
    <tr>
        <th>Item Name</th>
        <th>Price</th>
        <th>Labour</th>
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

    $sql = "SELECT * FROM item_list";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?=$row['item_name']?></td>
                <td><?=$row['item_price']?></td>
                <td><?=$row['item_labour']?></td>
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
            <div class="form-group hidden">
                <label for="itemname">Item No:</label>
                <input type="text" class="form-control" id="itemno" name="itemno" required value="<?=$row['srno']?>">
            </div>
            <div class="form-group">
                <label for="itemname">Item Name:</label>
                <input type="text" class="form-control" id="itemname" name="itemname" value="<?=$row['item_name']?>" required>
            </div>
            <div class="form-group">
                <label for="itemprice">Item Price:</label>
                <input type="number" class="form-control" id="itemprice" step="any" value="<?=$row['item_price']?>" name="itemprice" required>
            </div>
            <div class="form-group">
                <label for="itemlabour">Labour:</label>
                <input type="number" class="form-control" id="itemlabour" step="any" value="<?=$row['item_labour']?>" name="itemlabour" required>
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
    $conn->close(); ?>


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
                <label for="itemname">Item Name:</label>
                <input type="text" class="form-control" id="itemname" name="itemname" required>
            </div>
            <div class="form-group">
                <label for="itemprice">Item Price:</label>
                <input type="number" class="form-control" id="itemprice" step="any" name="itemprice" required>
            </div>
            <div class="form-group">
                <label for="itemlabour">Labour:</label>
                <input type="number" class="form-control" id="itemlabour" step="any" name="itemlabour" required>
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
