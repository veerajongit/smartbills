<?php
include "session.php";
if(isset($_GET['delete'])){
    $sql = "DELETE FROM reminder WHERE srno=".$_GET['delete'];
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->query($sql);
    header('location : home.php');
}


if(isset($_POST['text']) && $_POST['date'] && $_POST['period']){
    $sql = "
        INSERT INTO `reminder` (`srno`, `period`, `remindertext`, `date`, `datetime`) VALUES (NULL, '".$_POST['period']."', '".$_POST['text']."', '".date('Y-m-d', strtotime($_POST['date']))."', CURRENT_TIMESTAMP);
    ";
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->query($sql);
    header('location : home.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SmartBills - Home</title>
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
                "pageLength": 50
            });
        } );
    </script>
</head>
<body>
<?php include "navbar.php"; ?>

<div class="container">
    <div class="pull-right">
        <!-- Trigger the modal with a button -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add Reminder</button>
        <?php
        if(!isset($_GET['viewall'])){ ?>
            <button type="button" class="btn btn-warning" onclick="location.href='home.php?viewall=yes' ">View All</button>
        <?php }else{ ?>
            <button type="button" class="btn btn-warning" onclick="location.href='home.php' ">Todays List</button>
            <?php
        } ?>
    </div>
</div>
<div class="container">
    <?php
    if(isset($_GET['viewall'])){ ?>
    <h2>Reminder</h2>
    <table class="table" id="example">
        <thead>
        <tr>
            <th>Dated</th>
            <th>Text</th>
            <th>Reminder Period</th>
            <th>Action</th>
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
        //All Reminders
        $sql = "SELECT * FROM reminder ORDER BY date DESC";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?=date('d-m-Y', strtotime($row['date']))?></td>
                <td><?=$row['remindertext']?></td>
                <td>
                    <?php
                    if($row['period'] == 'm'){
                        echo 'Monthly';
                    }
                    if($row['period'] == 'd'){
                        echo 'Daily';
                    }
                    if($row['period'] == 'y'){
                        echo 'Yearly';
                    }
                    ?>
                </td>
                <td><button class="btn btn-danger" onclick=" location.href ='home.php?delete=<?=$row['srno']?>&viewall=yes' ">Delete</button></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
        <?php
    }else {
        ?>

        <h2>Reminder</h2>
        <table class="table" id="example">
            <thead>
            <tr>
                <th>Dated</th>
                <th>Text</th>
                <th>Reminder Period</th>
                <th>Action</th>
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
            //Monthly Reminders
            $date = date('Y-m-d');
            $lastdate = date('Y-m-t');
            if ($date == $lastdate && date('d', strtotime($date)) < date('t', strtotime($date))) {
                for ($i = date('d', strtotime($date)); $i <= date('t', strtotime($date)); $i++) {
                    $newdate = '%-%-' . $i;
                    $sql = "SELECT * FROM reminder WHERE date LIKE '" . $newdate . "' AND period='m'";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                            <td><?= $row['remindertext'] ?></td>
                            <td>
                                <?php
                                if ($row['period'] == 'm') {
                                    echo 'Monthly';
                                }
                                if ($row['period'] == 'd') {
                                    echo 'Daily';
                                }
                                if ($row['period'] == 'y') {
                                    echo 'Yearly';
                                }
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-danger"
                                        onclick=" location.href ='home.php?delete=<?= $row['srno'] ?>' ">Delete
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                }

            } else {
                $newdate = '%-%-' . date('d');
                $sql = "SELECT * FROM reminder WHERE date LIKE '" . $newdate . "' AND period='m'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                        <td><?= $row['remindertext'] ?></td>
                        <td>
                            <?php
                            if ($row['period'] == 'm') {
                                echo 'Monthly';
                            }
                            if ($row['period'] == 'd') {
                                echo 'Daily';
                            }
                            if ($row['period'] == 'y') {
                                echo 'Yearly';
                            }
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-danger"
                                    onclick=" location.href ='home.php?delete=<?= $row['srno'] ?>' ">Delete
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            }
            //Daily Reminders
            $sql = "SELECT * FROM reminder WHERE period='d'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                    <td><?= $row['remindertext'] ?></td>
                    <td>
                        <?php
                        if ($row['period'] == 'm') {
                            echo 'Monthly';
                        }
                        if ($row['period'] == 'd') {
                            echo 'Daily';
                        }
                        if ($row['period'] == 'y') {
                            echo 'Yearly';
                        }
                        ?>
                    </td>
                    <td>
                        <button class="btn btn-danger" onclick=" location.href ='home.php?delete=<?= $row['srno'] ?>' ">
                            Delete
                        </button>
                    </td>
                </tr>
                <?php
            }

            //Daily Reminders
            $date = date('Y-m-d');
            $lastdate = date('Y-m-t');
            if ($date == $lastdate && date('d', strtotime($date)) < date('t', strtotime($date))) {
                for ($i = date('d', strtotime($date)); $i <= date('t', strtotime($date)); $i++) {
                    $newdate = '%-' . date('m', strtotime($date)) . '-' . $i;
                    $sql = "SELECT * FROM reminder WHERE date LIKE '" . $newdate . "' AND period='y'";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                            <td><?= $row['remindertext'] ?></td>
                            <td>
                                <?php
                                if ($row['period'] == 'm') {
                                    echo 'Monthly';
                                }
                                if ($row['period'] == 'd') {
                                    echo 'Daily';
                                }
                                if ($row['period'] == 'y') {
                                    echo 'Yearly';
                                }
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-danger"
                                        onclick=" location.href ='home.php?delete=<?= $row['srno'] ?>' ">Delete
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                }

            } else {
                $newdate = '%-' . date('m', strtotime($date)) . '-' . date('d');
                $sql = "SELECT * FROM reminder WHERE date LIKE '" . $newdate . "' AND period='y'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                        <td><?= $row['remindertext'] ?></td>
                        <td>
                            <?php
                            if ($row['period'] == 'm') {
                                echo 'Monthly';
                            }
                            if ($row['period'] == 'd') {
                                echo 'Daily';
                            }
                            if ($row['period'] == 'y') {
                                echo 'Yearly';
                            }
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-danger"
                                    onclick=" location.href ='home.php?delete=<?= $row['srno'] ?>' ">Delete
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            }

            ?>
            </tbody>
        </table>
        </div>
        <?php
    }
    ?>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Reminder</h4>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="text" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="text">Reminder Text:</label>
                        <textarea type="text" class="form-control" id="text" name="text" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="period">Set Period:</label>
                        <select class="form-control" name="period" id="period" required>
                            <option value="d">Daily</option>
                            <option value="m">Monthly</option>
                            <option value="y">Yearly</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<script>
    $( function() {
        $( "#date" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );
</script>
</body>
</html>
