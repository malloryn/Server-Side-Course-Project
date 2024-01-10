<!-- 
Name: Mallory Newberry
Created on: 09/29/23
Modified on: 10/22/23
adminLogin.php -->

<?php  session_start(); //this must be the very first line on the php page, to register this page to use session variables
	$_SESSION['timeout'] = time(); //record the time at the user login 

	require_once "../util.php";
	require_once "../dbconnect.php";
	//always initialized variables to be used
	$msg = "";	
	$uname = "";
	$password = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../greeno/css/bootstrap.min.css">
    <!-- index.html style css -->
    <link rel="stylesheet" href="../greeno/css/style.css">
    <!-- page css -->
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../greeno/css/responsive.css">
</head>
<body>
    <!-- header -->
    <header>
        <!-- header inner -->
        <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                    <div class="full">
                    <div class="center-desk">
                        <div class="logo"> <a href="../greeno/index.html"><img src="../greeno/images/logo.png" alt="../greeno/#"></a> </div>
                    </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                    <div class="menu-area">
                    <div class="limit-box">
                        <nav class="main-menu">
                            <ul class="menu-area-main">
                                <li class="active"> <a href="../greeno/#">Home</a> </li>
                                <li><a href="../greeno/#about">About</a></li>
                                <li><a href="../greeno/#plant">Plants</a></li>
                                <li><a href="../greeno/#gallery">Showcase</a></li>
                            </ul>
                        </nav>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- end header inner -->
    </header>
    <!-- end header -->
    <?php
        if(isset($_POST['submit'])) {

            $uname = trim($_POST['username']);
            $password = trim($_POST['password']);

            //verify the username and password from the db
            if(spamcheck($uname)) { //checking for a valid email format

                $stmt = $con->prepare("select count(*) as c from ADMIN where email = ? and password = ?");
                $stmt->execute(array($uname, $password));
                $row = $stmt->fetch(PDO::FETCH_OBJ);
                
                $count = $row->c;

                if ($count == 1) {
                    $stmt = $con->prepare("Select AdminID from ADMIN where email = ? and password = ?");
                    $stmt->execute(array($uname, $password));
                    $row = $stmt->fetch(PDO::FETCH_OBJ);
                
                    $uid = $row->AdminID;

                    $_SESSION['uid'] = $uid;
                    $_SESSION['email'] = $uname;
                    Header ("Location:adminPortal.html");
                }
                else $msg = "The information entered does not match with the records in our database.";
            }
            else $msg = "Please enter a valid email.";
        }
    ?>
    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Admin Login</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <form action="#" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <!-- send admin to the admin portal -->
            <div class="center">
                <input type="submit" name="submit" value="Login">
            </div>
        </form>
    </div>
</body>
</html>