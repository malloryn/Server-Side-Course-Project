<!-- 
Name: Mallory Newberry
Created on: 09/03/23
Modified on: 11/11/23
editProfile.php -->

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
    <title>Edit Profile</title>
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
    <?php

        $stmt = $con->prepare("Select * from CUSTOMER where email = ?");
        $stmt->execute([$_SESSION['email']]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
    
        // Convert the customer object into an array
        $customer = get_object_vars($row);
    
        // Store the customer data in a session variable
        $_SESSION['customer'] = $customer;

        if (isset($_POST['editProfile'])) {
            // Handle when the edit form is submitted
            $editedData = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],  
                'address' => $_POST['address'],    
                'phone' => $_POST['phone'],      
            ];
    
            // Update the payment data in the database
            $updateStmt = $con->prepare("UPDATE CUSTOMER SET FirstName = ?, LastName = ?, Email = ?, Password = ?, Address = ?, Phone = ? WHERE CustomerID = ?");
            $updateStmt->execute([$editedData['first_name'], $editedData['last_name'], $editedData['email'], $editedData['password'], $editedData['address'], $editedData['phone'], $_SESSION['customer']['CustomerID']]);
    
            $msg = '<font color = "green">Your profile has been edited and saved.</font><br/>';
            // Clear the session variable after successful update
            unset($_SESSION['customer']);
        }
    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Edit Profile</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
        ?>

        <!-- Profile Editing Form -->
        <form method="post" action="#">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?php echo isset($_SESSION['customer']) ? $_SESSION['customer']['FirstName'] : ''; ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?php echo isset($_SESSION['customer']) ? $_SESSION['customer']['LastName'] : ''; ?>" required>

            <label for="email">Email:</label>
            <input type="text" name="email" value="<?php echo isset($_SESSION['customer']) ? $_SESSION['customer']['Email'] : ''; ?>" required>

            <label for="password">Password:</label>
            <input type="text" name="password" value="<?php echo isset($_SESSION['customer']) ? $_SESSION['customer']['Password'] : ''; ?>" required>

            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo isset($_SESSION['customer']) ? $_SESSION['customer']['Address'] : ''; ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo isset($_SESSION['customer']) ? $_SESSION['customer']['Phone'] : ''; ?>" required>

            <button type="submit" name="editProfile">Confirm</button>
        </form>
    </div>
</body>
</html>