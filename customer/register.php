<!-- 
Name: Mallory Newberry
Created on: 09/03/23
Modified on: 10/22/23
register.php -->

<?php  session_start(); //this must be the very first line on the php page, to register this page to use session variables
	$_SESSION['timeout'] = time(); //record the time at the user login 

	require_once "../util.php";
	require_once "../dbconnect.php";
	//always initialized variables to be used
	$msg = "";	
    $term = "You must agree to the terms and conditions";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                                <li class="active"> <a href="../greeno/#">Home</a></li>
                                <li><a href="../greeno/#plant">Plants</a></li>
                                <li><a href="../greeno/#gallery">Showcase</a></li>
                                <li><a href="register.php">Login/Register</a></li>
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
            // Initializing variables
            $first_name = "";
            $last_name = "";
            $uname = "";
            $password = "";
            $confirm_password = "";
            $address = "";
            $phone = "";
            $agree = "";

            $unameok = false;
            $pwdok = false;
            $agreeok = false;

            function sanitize_input($input) {
                return htmlspecialchars(trim($input));
            }

            function validate_email($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            }

            function validate_password($password) {
                // Password must be at least 8 characters long and contain both letters and numbers
                return strlen($password) >= 8 && preg_match('/[a-zA-Z]/', $password) && preg_match('/\d/', $password);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $first_name = sanitize_input(filter_input(INPUT_POST, 'first_name'));
                $last_name = sanitize_input(filter_input(INPUT_POST, 'last_name'));
                $uname = sanitize_input(filter_input(INPUT_POST, 'uname', FILTER_VALIDATE_EMAIL));
                $password = sanitize_input(filter_input(INPUT_POST, 'password'));
                $confirm_password = sanitize_input(filter_input(INPUT_POST, 'confirm_password'));
                $address = sanitize_input(filter_input(INPUT_POST, 'address'));
                $phone = sanitize_input(filter_input(INPUT_POST, 'phone'));
                $agree = isset($_POST['agree']) ? "Yes" : "No";

				if (!spamcheck($uname))							
					$msg = $msg . '<br/><b>Email is not valid. Please try again.</b>';
				else $unameok = true;

				if (!validate_password($password))
					$msg = $msg . '<br/><b>Password is not in the required format. Please try again.</b>';
				else {
					if ($password != $confirm_password)
						$msg = $msg . '<br/><b>Passwords are not the same. Please try again.</b>';
					else $pwdok = true;
				}
                if (!isset($_POST['agree'])) {
					$msg = $msg .  "<br/><b> You must agree to the terms and conditions.</b><br />";
					$term = '<span style="color:red">You must agree to the terms and conditions.</span>';

				}
				else $agreeok = true;

                if ($unameok && $pwdok && $agreeok) {
					//enter data into the database				
					$stmt = $con->prepare("insert into CUSTOMER values(null, ?, ?, ?, ?, ?, ?)");
					if ($stmt->execute(array($first_name, $last_name, $uname, $password, $address, $phone ))==TRUE)
						$msg = '<font color = "green">Thank you for registering. Please login.</font><br/>';
					else $msg = "Your information cannot be entered this time. Please try again later.";
					
				}
            }
        ?>
    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Register</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <form action="#" method="post">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="uname" id="uname" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Password must be at least 8 characters long and contain both letters and numbers" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="address">Full Home Address</label>
                <input type="text" name="address" id="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" required>
            </div>
            <div class="form-group">
                <input type="checkbox" name="agree" required> Agree to terms and policies
            </div>
            <div class="center">
                <input name="enter" class="btn" type="submit" value="Register" />
            </div>
        </form>
        <div class="normal_text">
            <p>Already have an account?</p>
            <a href="login.php">Login here!</a> 
        </div>
    </div>
</body>
</html>