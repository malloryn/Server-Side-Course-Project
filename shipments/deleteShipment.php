<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/10/23
deleteShipment.php -->

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
    <title>Delete Shipments</title>
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
        $shipmentStmt = $con->prepare("SELECT ShipmentID FROM SHIPMENT");
        $shipmentStmt->execute();
        $shipments = $shipmentStmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize variables to store the selected payment data
        $selectedShipment = "";

        if (isset($_POST['selectShipment'])) {
            // Handle when an payment is selected
            $selectedShipmentID = $_POST['shipmentID'];

            // Fetch the payment data based on the selected ID
            $shipmentDataStmt = $con->prepare("SELECT * FROM SHIPMENT WHERE ShipmentID = ?");
            $shipmentDataStmt->execute([$selectedShipmentID]);
            $selectedShipment = $shipmentDataStmt->fetch(PDO::FETCH_ASSOC);

            // Store the selected payment's data in a session variable
            $_SESSION['selectedShipment'] = $selectedShipment;
        }

        if (isset($_POST['deleteShipment']) && is_array($_SESSION['selectedShipment'])) {
            // Delete the shipment from the database
            $deleteStmt = $con->prepare("DELETE FROM `SHIPMENT` WHERE ShipmentID = ?");
            $deleteStmt->execute([$_SESSION['selectedShipment']['ShipmentID']]);

            $msg = '<font color = "green">Your shipment has been cancelled and deleted.</font><br/>';
            // Clear the session variable after successful delete
            unset($_SESSION['selectedShipment']);
        }
    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Delete Shipment</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
        ?>
        <!-- Payment Selection Form -->
        <form method="post" action="#">
            <label for="shipmentID">Select Shipment ID:</label>
            <select name="shipmentID" id="shipmentID" required>
                <?php foreach ($shipments as $shipment): ?>
                    <option value="<?= $shipment['ShipmentID'] ?>"><?= $shipment['ShipmentID'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="selectShipment">Select Shipment</button>
        </form>

        <!-- Payment Deleting Form -->
        <form method="post" action="#" onsubmit="return confirmDelete();">
            <button type="submit" name="deleteShipment" id="deleteButton">Delete Shipment</button>
        </form>

        <script>
        function confirmDelete() {
            var result = confirm("Are you sure you want to delete this shipment?");
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