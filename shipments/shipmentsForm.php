<!-- 
Name: Mallory Newberry
Created on: 09/29/23
Modified on: 10/1/23
shipmentsForm.php -->

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
    <title>Add a Shipment</title>
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
            $customerId = "";
            $orderId = "";
            $transportation = "";

            function sanitize_input($input) {
                return htmlspecialchars(trim($input));
            }

            $customerId = sanitize_input(filter_input(INPUT_POST, 'customer'));
            $orderId = sanitize_input(filter_input(INPUT_POST, 'order'));
            $transportation = sanitize_input(filter_input(INPUT_POST, 'transportation'));

            if ($customerId !== "" && $orderId !== "" && $transportation !== "") {
                // Get the current date formatted as yyyy-mm-dd
                $currentDate = date('Y-m-d');

                //enter data into the database
                $stmt = $con->prepare("insert into SHIPMENT values(null, ?, ?, ?, ?)");
                if ($stmt->execute(array($customerId, $orderId, $transportation, $currentDate))==TRUE)
                    $msg = '<font color = "green">Your shipment has been added!</font><br/>';
                else $msg = "Your information cannot be entered this time. Please try again later.";
            }
        }

        // Fetch customer ids from the database
        $customerStmt = $con->prepare("SELECT CustomerID FROM `CUSTOMER`");
        $customerStmt->execute();
        $customers = $customerStmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch order ids from the database
        $orderStmt = $con->prepare("SELECT OrderID FROM `ORDER`");
        $orderStmt->execute();
        $orders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Add a New Shipment</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <form action="#" method="post">
            <div class="form-group">
                <label for="customer">Choose Customer by ID number:</label><br>
                <select name="customer" id="customer" required>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= $customer['CustomerID'] ?>"><?= $customer['CustomerID'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="order">Choose Order by ID number:</label><br>
                <select name="order" id="order" required>
                    <?php foreach ($orders as $order): ?>
                        <option value="<?= $order['OrderID'] ?>"><?= $order['OrderID'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="transportation">Transportation Method</label></br>
                <select name="transportation" id="transportation" required>
                    <option value="Air freight">Air freight</option>
                    <option value="Ocean freight">Ocean freight</option>
                    <option value="Ground freight">Ground freight</option>
                    <option value="Multimodal freight">Multimodal freight</option>
                </select>
            </div>
            <div class="center">
                <input name="enter" class="btn" type="submit" value="Submit" />
            </div>
        </form>
    </div>
</body>
</html>