<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/10/23
deleteNursery.php -->

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
    <title>Delete Nurseries</title>
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
        $nurseryStmt = $con->prepare("SELECT NurseryID FROM NURSERY");
        $nurseryStmt->execute();
        $nurseries = $nurseryStmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize variables to store the selected payment data
        $selectedNursery = "";

        if (isset($_POST['selectNursery'])) {
            // Handle when an payment is selected
            $selectedNurseryID = $_POST['nurseryID'];

            // Fetch the payment data based on the selected ID
            $nurseryDataStmt = $con->prepare("SELECT * FROM NURSERY WHERE NurseryID = ?");
            $nurseryDataStmt->execute([$selectedNurseryID]);
            $selectedNursery = $nurseryDataStmt->fetch(PDO::FETCH_ASSOC);

            // Store the selected payment's data in a session variable
            $_SESSION['selectedNursery'] = $selectedNursery;
        }

        if (isset($_POST['deleteNursery']) && is_array($_SESSION['selectedNursery'])) {
            // Handle when the delete form is submitted
            // Delete the related plant from the database
            $deletePlantStmt = $con->prepare("DELETE FROM PLANT WHERE NurseryID = ?");
            $deletePlantStmt->execute([$_SESSION['selectedNursery']['NurseryID']]);

            // Delete the nursery from the database
            $deleteStmt = $con->prepare("DELETE FROM NURSERY WHERE NurseryID = ?");
            $deleteStmt->execute([$_SESSION['selectedNursery']['NurseryID']]);

            $msg = '<font color = "green">Your nursery has been deleted.</font><br/>';
            // Clear the session variable after successful delete
            unset($_SESSION['selectedNursery']);
        }
    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Delete Nursery</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
        ?>
        <!-- Payment Selection Form -->
        <form method="post" action="#">
            <label for="nurseryID">Select Nursery ID:</label>
            <select name="nurseryID" id="nurseryID" required>
                <?php foreach ($nurseries as $nursery): ?>
                    <option value="<?= $nursery['NurseryID'] ?>"><?= $nursery['NurseryID'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="selectNursery">Select Nursery</button>
        </form>

        <!-- Payment Deleting Form -->
        <form method="post" action="#" onsubmit="return confirmDelete();">
            <button type="submit" name="deleteNursery" id="deleteButton">Delete Nursery</button>
        </form>

        <script>
        function confirmDelete() {
            var result = confirm("Are you sure you want to delete this nursery?");
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