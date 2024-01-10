<!-- 
Name: Mallory Newberry
Created on: 10/22/23
Modified on: 10/22/23
viewProfile.php -->

<?php  session_start(); //this must be the very first line on the php page, to register this page to use session variables
	$_SESSION['timeout'] = time(); //record the time at the user login 

	require_once "../util.php";
	require_once "../dbconnect.php";
	//always initialized variables to be used
	$msg = "";	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../greeno/css/bootstrap.min.css">
    <!-- index.html style css -->
    <link rel="stylesheet" href="../greeno/css/style.css">
    <!-- register page css -->
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
                                <li><a href="../greeno/#plant">Plants</a></li>
                                <li><a href="../greeno/#gallery">Showcase</a></li>
                                <li><a href="customerPortal.html">Back to Customer Page</a></li>
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
    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Profile</h1>
        </div>
        <div class="normal_text">
            <a href="editProfile.php">Edit</a> 
        </div>

        <?php
            $stmt = $con->prepare("select * from CUSTOMER where email = ?");
            $stmt->execute([$_SESSION['email']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            print "First Name: ".$row["FirstName"] . "<br />";
            print "Last Name: ".$row ["LastName"] . "<br />";
            print "Email: ".$row["Email"] . "<br />";
            print "Password: ".$row["Password"] . "<br />";
            print "Address: ".$row["Address"] . "<br />";
            print "Phone: ".$row["Phone"] . "<br />";

        ?>

    </div>
</body>
</html>