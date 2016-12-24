<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">SmartBills<?php if(isset($table_name)){ echo " : ".ucfirst($table_name); } ?></a>
        </div>
        <ul class="nav navbar-nav">
            <?php if(basename($_SERVER['PHP_SELF']) == "home.php"){ ?><li class="active"><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li><?php }else{ ?><li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li><?php } ?>
            <?php if(basename($_SERVER['PHP_SELF']) == "purchasebills.php"){ ?><li class="active"><a href="purchasebills.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Purchase</a></li><?php }else{ ?><li><a href="purchasebills.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Purchase</a></li><?php } ?>
            <?php if(basename($_SERVER['PHP_SELF']) == "salesbills.php"){ ?><li class="active"><a href="salesbills.php"><i class="fa fa-money" aria-hidden="true"></i> Sales</a></li><?php }else{ ?><li><a href="salesbills.php"><i class="fa fa-money" aria-hidden="true"></i> Sales</a></li><?php } ?>
            <?php if(basename($_SERVER['PHP_SELF']) == "purchasereport.php"){ ?><li class="active"><a href="purchasereport.php"><i class="fa fa-area-chart" aria-hidden="true"></i> Purchase Report</a></li><?php }else{ ?><li><a href="purchasereport.php"><i class="fa fa-area-chart" aria-hidden="true"></i> Purchase Report</a></li><?php } ?>
            <?php if(basename($_SERVER['PHP_SELF']) == "salereport.php"){ ?><li class="active"><a href="salereport.php"><i class="fa fa-area-chart" aria-hidden="true"></i> Sales Report</a></li><?php }else{ ?><li><a href="salereport.php"><i class="fa fa-area-chart" aria-hidden="true"></i> Sales Report</a></li><?php } ?>
            <?php if(basename($_SERVER['PHP_SELF']) == "address.php"){ ?><li class="active"><a href="address.php"><i class="fa fa-address-book" aria-hidden="true"></i> Address</a></li><?php }else{ ?><li><a href="address.php"><i class="fa fa-address-book" aria-hidden="true"></i> Address</a></li><?php } ?>
            <?php if(basename($_SERVER['PHP_SELF']) == "itemlist.php"){ ?><li class="active"><a href="itemlist.php"><i class="fa fa-archive" aria-hidden="true"></i> Item List</a></li><?php }else{ ?><li><a href="itemlist.php"><i class="fa fa-archive" aria-hidden="true"></i> Item List</a></li><?php } ?>
            <?php if(basename($_SERVER['PHP_SELF']) == "workers.php"){ ?><li class="active"><a href="workers.php"><i class="fa fa-users" aria-hidden="true"></i> Workers</a></li><?php }else{ ?><li><a href="workers.php"><i class="fa fa-users" aria-hidden="true"></i> Workers</a></li><?php } ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user-circle" aria-hidden="true"></i>
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#" data-toggle="modal" data-target="#pwd"><i class="fa fa-gear" aria-hidden="true"></i> Change Password</a></li>
                    <li><a href="index.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Modal -->
<div id="pwd" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change Password</h4>
            </div>
            <div class="modal-body">
                <form onsubmit="return passwordchange()" method="post" name="myForm">
                    <div class="form-group">
                        <label for="oldpwd">Type Old Password:</label>
                        <input type="password" class="form-control" id="oldpwd" name="oldpwd" required>
                    </div>
                    <div class="form-group">
                        <label for="newpwd">Type New Password:</label>
                        <input type="password" class="form-control" id="newpwd" name="newpwd" required>
                    </div>
                    <div class="form-group">
                        <label for="retypepwd">Retype New Password:</label>
                        <input type="password" class="form-control" id="retypepwd" name="retypepwd" required>
                    </div>
                    <input type="submit" class="btn btn-default" value="Save">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="successpwd" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Success!</h4>
            </div>
            <div class="modal-body">
                <p>Password Changed Successful.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="failedpwd" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Failed!</h4>
            </div>
            <div class="modal-body">
                <p>Password Changed Failed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>


<script>
    function passwordchange(){
        var oldpwd = document.forms["myForm"]["oldpwd"].value;
        var newpwd = document.forms["myForm"]["newpwd"].value;
        var retypepwd = document.forms["myForm"]["retypepwd"].value;
        if(retypepwd != newpwd){
            alert("New password did'nt match with retype password.");
            document.forms["myForm"]["oldpwd"].value = '';
            document.forms["myForm"]["newpwd"].value = '';
            document.forms["myForm"]["retypepwd"].value = '';
            return false;
        }else{
            if(oldpwd == newpwd){
                alert("Old password and new password are same. Please use different password.");
                document.forms["myForm"]["oldpwd"].value = '';
                document.forms["myForm"]["newpwd"].value = '';
                document.forms["myForm"]["retypepwd"].value = '';
                return false;
            }
            var datanew = {
                oldpwd : oldpwd,
                newpwd : newpwd,
                retypepwd : retypepwd
            };
            $.post( "changepassword.php", datanew)
                .done(function( data ) {
                    if(data.trim() == "success"){
                        $('#pwd').modal('hide');
                        $('#successpwd').modal('show');
                    }else{
                        $('#pwd').modal('hide');
                        $('#failedpwd').modal('show');
                    }
                });
        }
        document.forms["myForm"]["oldpwd"].value = '';
        document.forms["myForm"]["newpwd"].value = '';
        document.forms["myForm"]["retypepwd"].value = '';
        return false;
    }
</script>