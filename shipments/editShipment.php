<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/10/23
editShipment.php -->

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
    <title>Edit Shipments</title>
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
       // Fetch shipment IDs from the database
       $shipmentStmt = $con->prepare("SELECT ShipmentID FROM SHIPMENT");
       $shipmentStmt->execute();
       $shipments = $shipmentStmt->fetchAll(PDO::FETCH_ASSOC);
   
       // Initialize variables to store the selected shipment data
       $selectedShipment = "";
   
       if (isset($_POST['selectShipment'])) {
           // Handle when a shipment is selected
           $selectedShipmentID = $_POST['shipmentID'];
   
           // Fetch the shipment data based on the selected ID
           $shipmentDataStmt = $con->prepare("SELECT * FROM SHIPMENT WHERE ShipmentID = ?");
           $shipmentDataStmt->execute([$selectedShipmentID]);
           $selectedShipment = $shipmentDataStmt->fetch(PDO::FETCH_ASSOC);
   
           // Store the selected shipment's data in a session variable
           $_SESSION['selectedShipment'] = $selectedShipment;
       }

        if (isset($_POST['editShipment'])&& is_array($_SESSION['selectedShipment'])) {
            // Handle when the edit form is submitted
            $editedData = [
                'transportation' => $_POST['transportation'],
            ];
    
            // Update the shipment data in the database
            $updateStmt = $con->prepare("UPDATE SHIPMENT SET TransportationMethod = ? WHERE ShipmentID = ?");
            $updateStmt->execute([$editedData['transportation'], $_SESSION['selectedShipment']['ShipmentID']]);
    
            $msg = '<font color = "green">Your shipment has been edited and saved.</font><br/>';
            // Clear the session variable after successful update
            unset($_SESSION['selectedShipment']);
        }

    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Edit Shipment</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <!-- Shipment Selection Form -->
        <form method="post" action="#">
            <label for="shipmentID">Select Shipment ID:</label>
            <select name="shipmentID" id="shipmentID" required>
                <?php foreach ($shipments as $shipment): ?>
                    <option value="<?= $shipment['ShipmentID'] ?>"><?= $shipment['ShipmentID'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="selectShipment">Select Shipment</button>
        </form>

        <!-- Shipment Editing Form -->
            <form method="post" action="#">
                <label for="transportation">Transportation Method:</label>
                <input type="text" name="transportation" value="<?php echo isset($_SESSION['selectedShipment']) ? $_SESSION['selectedShipment']['TransportationMethod'] : ''; ?>" required>

                <button type="submit" name="editShipment">Confirm</button>
            </form>
    </div>
</body>
</html>