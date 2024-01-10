<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/11/23
editAdminInfo.php -->

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
    <title>Edit Admin Info</title>
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
       // Fetch admin IDs from the database
       $adminStmt = $con->prepare("SELECT AdminID FROM ADMIN");
       $adminStmt->execute();
       $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);
   
       // Initialize variables to store the selected admin data
       $selectedAdmin = "";
   
       if (isset($_POST['selectAdmin'])) {
           // Handle when a admin is selected
           $selectedAdminID = $_POST['adminID'];
   
           // Fetch the admin data based on the selected ID
           $adminDataStmt = $con->prepare("SELECT * FROM ADMIN WHERE AdminID = ?");
           $adminDataStmt->execute([$selectedAdminID]);
           $selectedAdmin = $adminDataStmt->fetch(PDO::FETCH_ASSOC);
   
           // Store the selected admin's data in a session variable
           $_SESSION['selectedAdmin'] = $selectedAdmin;
       }

        if (isset($_POST['editAdmin'])&& is_array($_SESSION['selectedAdmin'])) {
            // Handle when the edit form is submitted
            $editedData = [
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'job_title' => $_POST['job_title'],
            ];
    
            // Update the admin data in the database
            $updateStmt = $con->prepare("UPDATE ADMIN SET FirstName = ?, LastName = ?, Email = ?, Password = ?, JobTitle = ? WHERE AdminID = ?");
            $updateStmt->execute([$editedData['first_name'], $editedData['last_name'], $editedData['email'], $editedData['password'], $editedData['job_title'], $_SESSION['selectedAdmin']['AdminID']]);
    
            $msg = '<font color = "green">Your admin information has been edited and saved.</font><br/>';
            // Clear the session variable after successful update
            unset($_SESSION['selectedAdmin']);
        }

    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Edit Admin Info</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <!-- Admin Selection Form -->
        <form method="post" action="#">
            <label for="adminID">Select Admin ID:</label>
            <select name="adminID" id="adminID" required>
                <?php foreach ($admins as $admin): ?>
                    <option value="<?= $admin['AdminID'] ?>"><?= $admin['AdminID'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="selectAdmin">Select Admin</button>
        </form>

        <!-- Admin Editing Form -->
        <form method="post" action="#">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="<?php echo isset($_SESSION['selectedAdmin']) ? $_SESSION['selectedAdmin']['FirstName'] : ''; ?>" required>
        
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="<?php echo isset($_SESSION['selectedAdmin']) ? $_SESSION['selectedAdmin']['LastName'] : ''; ?>" required>

            <label for="email">Email:</label>
            <input type="text" name="email" value="<?php echo isset($_SESSION['selectedAdmin']) ? $_SESSION['selectedAdmin']['Email'] : ''; ?>" required>

            <label for="password">Password:</label>
            <input type="text" name="password" value="<?php echo isset($_SESSION['selectedAdmin']) ? $_SESSION['selectedAdmin']['Password'] : ''; ?>" required>

            <label for="job_title">Job Title:</label>
            <input type="text" name="job_title" value="<?php echo isset($_SESSION['selectedAdmin']) ? $_SESSION['selectedAdmin']['JobTitle'] : ''; ?>" required>

            <button type="submit" name="editAdmin">Confirm</button>
        </form>
    </div>
</body>
</html>