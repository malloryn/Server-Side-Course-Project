<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/11/23
editNursery.php -->

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
    <title>Edit Nurseries</title>
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
       // Fetch nursery IDs from the database
       $nurseryStmt = $con->prepare("SELECT NurseryID FROM NURSERY");
       $nurseryStmt->execute();
       $nurseries = $nurseryStmt->fetchAll(PDO::FETCH_ASSOC);
   
       // Initialize variables to store the selected nursery data
       $selectedNursery = "";
   
       if (isset($_POST['selectNursery'])) {
           // Handle when a nursery is selected
           $selectedNurseryID = $_POST['nurseryID'];
   
           // Fetch the nursery data based on the selected ID
           $nurseryDataStmt = $con->prepare("SELECT * FROM NURSERY WHERE NurseryID = ?");
           $nurseryDataStmt->execute([$selectedNurseryID]);
           $selectedNursery = $nurseryDataStmt->fetch(PDO::FETCH_ASSOC);
   
           // Store the selected nursery's data in a session variable
           $_SESSION['selectedNursery'] = $selectedNursery;
       }

        if (isset($_POST['editNursery'])&& is_array($_SESSION['selectedNursery'])) {
            // Handle when the edit form is submitted
            $editedData = [
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'manager_name' => $_POST['manager_name'],
            ];
    
            // Update the nursery data in the database
            $updateStmt = $con->prepare("UPDATE NURSERY SET Name = ?, Address = ?, Email = ?, Phone = ?, ManagerName = ? WHERE NurseryID = ?");
            $updateStmt->execute([$editedData['name'], $editedData['address'], $editedData['email'], $editedData['phone'], $editedData['manager_name'], $_SESSION['selectedNursery']['NurseryID']]);
    
            $msg = '<font color = "green">Your nursery has been edited and saved.</font><br/>';
            // Clear the session variable after successful update
            unset($_SESSION['selectedNursery']);
        }

    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Edit Nursery</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <!-- Nursery Selection Form -->
        <form method="post" action="#">
            <label for="nurseryID">Select Nursery ID:</label>
            <select name="nurseryID" id="nurseryID" required>
                <?php foreach ($nurseries as $nursery): ?>
                    <option value="<?= $nursery['NurseryID'] ?>"><?= $nursery['NurseryID'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="selectNursery">Select Nursery</button>
        </form>

        <!-- Nursery Editing Form -->
        <form method="post" action="#">
            <label for="name">Nursery Name:</label>
            <input type="text" name="name" value="<?php echo isset($_SESSION['selectedNursery']) ? $_SESSION['selectedNursery']['Name'] : ''; ?>" required>
        
            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo isset($_SESSION['selectedNursery']) ? $_SESSION['selectedNursery']['Address'] : ''; ?>" required>

            <label for="email">Email:</label>
            <input type="text" name="email" value="<?php echo isset($_SESSION['selectedNursery']) ? $_SESSION['selectedNursery']['Email'] : ''; ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo isset($_SESSION['selectedNursery']) ? $_SESSION['selectedNursery']['Phone'] : ''; ?>" required>

            <label for="manager_name">Manager Name:</label>
            <input type="text" name="manager_name" value="<?php echo isset($_SESSION['selectedNursery']) ? $_SESSION['selectedNursery']['ManagerName'] : ''; ?>" required>

            <button type="submit" name="editNursery">Confirm</button>
        </form>
    </div>
</body>
</html>