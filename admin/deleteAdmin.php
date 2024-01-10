<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/10/23
deleteAdmin.php -->

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
    <title>Remove Admins</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../greeno/css/bootstrap.min.css">
    <!-- index.html style css -->
    <link rel="stylesheet" href="../greeno/css/style.css">
    <!-- register page css -->
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../greeno/css/responsive.css">
    <!-- table css -->
    <link rel="stylesheet" href="../table_style.css">
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
        // Fetch payment IDs from the database
        $adminStmt = $con->prepare("SELECT AdminID FROM ADMIN");
        $adminStmt->execute();
        $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize variables to store the selected payment data
        $selectedAdmin = "";

        if (isset($_POST['selectAdmin'])) {
            // Handle when an payment is selected
            $selectedAdminID = $_POST['adminID'];

            // Fetch the payment data based on the selected ID
            $adminDataStmt = $con->prepare("SELECT * FROM ADMIN WHERE AdminID = ?");
            $adminDataStmt->execute([$selectedAdminID]);
            $selectedAdmin = $adminDataStmt->fetch(PDO::FETCH_ASSOC);

            // Store the selected payment's data in a session variable
            $_SESSION['selectedAdmin'] = $selectedAdmin;
        }

        if (isset($_POST['deleteAdmin']) && is_array($_SESSION['selectedAdmin'])) {
            // Handle when the delete form is submitted
            // Delete the payment from the database
            $deleteStmt = $con->prepare("DELETE FROM ADMIN WHERE AdminID = ?");
            $deleteStmt->execute([$_SESSION['selectedAdmin']['AdminID']]);

            $msg = '<font color = "green">This admin has been deleted.</font><br/>';
            // Clear the session variable after successful delete
               unset($_SESSION['selectedAdmin']);
        }
    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Remove Admin</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
        ?>
        <!-- Payment Selection Form -->
        <form method="post" action="#">
            <label for="adminID">Select Admin ID:</label>
            <select name="adminID" id="adminID" required>
                <?php foreach ($admins as $admin): ?>
                    <option value="<?= $admin['AdminID'] ?>"><?= $admin['AdminID'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="selectAdmin">Select Admin</button>
        </form>

        <!-- Payment Deleting Form -->
        <form method="post" action="#" onsubmit="return confirmDelete();">
            <button type="submit" name="deleteAdmin" id="deleteButton">Delete Admin</button>
        </form>

        <script>
        function confirmDelete() {
            var result = confirm("Are you sure you want to delete this admin?");
            if (result) {
                // If the user clicks OK, return true to submit the form
                return true;
            } else {
                // If the user clicks Cancel, return false to prevent the form from being submitted
                return false;
            }
        }
        </script>
    </div>
</body>
</html>