<!-- 
Name: Mallory Newberry
Created on: 10/20/23
Modified on: 10/21/23
nurseriesForm.php -->

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
    <title>Add a Nursery</title>
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
                                <li class="active"> <a href="../admin/adminPortal.html">Back to Admin Portal</a> </li>
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
        if (isset($_POST['enter'])) {
            // Initializing variables
            $name = "";
            $address = "";
            $email = "";
            $phone = "";
            $manager_name = "";

            function sanitize_input($input) {
                return htmlspecialchars(trim($input));
            }

            $name = sanitize_input(filter_input(INPUT_POST, 'name'));
            $address = sanitize_input(filter_input(INPUT_POST, 'address'));
            $email = sanitize_input(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
            $phone = sanitize_input(filter_input(INPUT_POST, 'phone'));
            $manager_name = sanitize_input(filter_input(INPUT_POST, 'manager_name'));

            if ($name !== NULL) {
                //enter data into the database
                $stmt = $con->prepare("insert into NURSERY values(null, ?, ?, ?, ?, ?)");
                if ($stmt->execute(array($name, $address, $email, $phone, $manager_name))==TRUE)
                    $msg = '<font color = "green">Your nursery has been added!</font><br/>';
                else $msg = "Your information cannot be entered this time. Please try again later.";
            }
        }
    ?>
    
    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Add a New Nursery</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <form action="#" method="post">
            <div class="form-group">
                <label for="name">Nursery Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" required>
            </div>
            <div class="form-group">
                <label for="manager_name">Manager Full Name</label>
                <input type="text" name="manager_name" id="manager_name">
            </div>
            <div class="center">
                <input name="enter" class="btn" type="submit" value="Submit" />
            </div>
        </form>
    </div>
</body>
</html>