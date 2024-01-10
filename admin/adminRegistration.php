<!-- 
Name: Mallory Newberry
Created on: 09/29/23
Modified on: 10/1/23
adminRegistration.php -->

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
    <title>Register an Admin</title>
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
                                <li class="active"> <a href="adminPortal.html">Back to Admin Portal</a> </li>
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
            $first_name = "";
            $last_name = "";
            $uname = "";
            $password = "";
            $confirm_password = "";
            $job_title = "";

            $unameok = false;
            $pwdok = false;

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

                $first_name = sanitize_input(filter_input(INPUT_POST, 'first_name'));
                $last_name = sanitize_input(filter_input(INPUT_POST, 'last_name'));
                $uname = sanitize_input(filter_input(INPUT_POST, 'uname', FILTER_VALIDATE_EMAIL));
                $password = sanitize_input(filter_input(INPUT_POST, 'password'));
                $confirm_password = sanitize_input(filter_input(INPUT_POST, 'confirm_password'));
                $job_title = sanitize_input(filter_input(INPUT_POST, 'job_title'));

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

                if ($unameok && $pwdok) {
                    //enter data into the database
                    $stmt = $con->prepare("insert into ADMIN values(null, ?, ?, ?, ?, ?)");
                    if ($stmt->execute(array($first_name, $last_name, $uname, $password, $job_title))==TRUE)
                        $msg = '<font color = "green">Your admin has been registered.</font><br/>';
                    else $msg = "Your information cannot be entered this time. Please try again later.";
                }
        }
    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Register an Admin</h1>
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
                <label for="">Job Title</label>
                <input type="text" name="job_title" id="job_title">
            </div>
            <div class="center">
                <input name="enter" class="btn" type="submit" value="Register Admin" />
            </div>
        </form>
    </div>
</body>
</html>