<?php
session_start();
if(isset($_POST['username']) && isset($_POST['password'])){
    include "db.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM login WHERE username = '".$_POST['username']."' AND password = '".$_POST['password']."' ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
            $_SESSION['userid'] = $row['srno'];
            $_SESSION['work'] = $_POST['type'];
            echo "success";
        }
    }
    exit;
}


// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
?>

<html>
    <head>
        <title>SmartBills</title>
        <link href='css/opensans.css' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            function submit(){
                var username = document.getElementById("username").value;
                var password = document.getElementById("password").value;
                var type = document.getElementById("type").value;
                var data = {
                    username : username,
                    password : password,
                    type : type
                };
                $.post( "index.php", data)
                    .done(function( data1 ) {
                        if(data1.trim() == 'success'){
                            location.href = 'home.php';
                        }else{
                            $('#myModal').modal('show');
                        }
                    });
            }
        </script>
        <style>
            body{
                font-family: 'Open Sans';
            }
            .login-page {
                width: 360px;
                padding: 8% 0 0;
                margin: auto;
            }
            .form {
                position: relative;
                z-index: 1;
                background: #FFFFFF;
                max-width: 360px;
                margin: 0 auto 100px;
                padding: 45px;
                text-align: center;
                box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }
            .form input {
                outline: 0;
                background: #f2f2f2;
                width: 100%;
                border: 0;
                margin: 0 0 15px;
                padding: 15px;
                box-sizing: border-box;
                font-size: 14px;
            }
            .form button {
                text-transform: uppercase;
                outline: 0;
                background: #5BC0DE;
                width: 100%;
                border: 0;
                padding: 15px;
                color: #ffffff;
                font-weight: bold;
                font-size: 14px;
                cursor: pointer;
            }
            .form button:hover,.form button:active,.form button:focus {
                background: #51ACC7;
            }
            .form .message {
                margin: 15px 0 0;
                color: #b3b3b3;
                font-size: 12px;
            }
            .form .message a {
                color: #4CAF50;
                text-decoration: none;
            }
            .form .register-form {
                display: none;
            }
            .container {
                position: relative;
                z-index: 1;
                max-width: 300px;
                margin: 0 auto;
            }
            .container:before, .container:after {
                content: "";
                display: block;
                clear: both;
            }
            .container .info {
                margin: 50px auto;
                text-align: center;
            }
            .container .info h1 {
                margin: 0 0 15px;
                padding: 0;
                font-size: 36px;
                font-weight: 300;
                color: #1a1a1a;
            }
            .container .info span {
                color: #4d4d4d;
                font-size: 12px;
            }
            .container .info span a {
                color: #000000;
                text-decoration: none;
            }
            .container .info span .fa {
                color: #EF3B3A;
            }
            body {
                background : url('img/bg.jpg');
            }
        </style>
    </head>
    <body>
    <div class="login-page" id="wrap">
        <div class="form">
            <h2>SmartBills</h2>
            <div class="login-form">
                <input type="text" placeholder="username" id="username"/>
                <input type="password" placeholder="password" id="password"/>
                <select id="type" style="margin-top: 5px; margin-bottom: 20px" class="form-control">
                    <option value="precision">Precision</option>
                    <option value="fine">Fine</option>
                </select>
                <button type="button" onclick="submit()">login <i class="fa fa-sign-in" aria-hidden="true"></i></button>
            </div>
        </div>
        <div id="push"></div>
    </div>
    <?php include "footer.php" ?>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="color:red">Login Failed!</h4>
                </div>
                <div class="modal-body">
                    <p>Username or Password Incorrect.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    </body>
</html>