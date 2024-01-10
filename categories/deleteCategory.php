<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/10/23
deleteCategory.php -->

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
    <title>Delete Categories</title>
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
        $categoryStmt = $con->prepare("SELECT CategoryID FROM CATEGORY");
        $categoryStmt->execute();
        $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize variables to store the selected payment data
        $selectedCategory = "";

        if (isset($_POST['selectCategory'])) {
            // Handle when an payment is selected
            $selectedCategoryID = $_POST['categoryID'];

            // Fetch the payment data based on the selected ID
            $categoryDataStmt = $con->prepare("SELECT * FROM CATEGORY WHERE CategoryID = ?");
            $categoryDataStmt->execute([$selectedCategoryID]);
            $selectedCategory = $categoryDataStmt->fetch(PDO::FETCH_ASSOC);

            // Store the selected payment's data in a session variable
            $_SESSION['selectedCategory'] = $selectedCategory;
        }

        if (isset($_POST['deleteCategory']) && is_array($_SESSION['selectedCategory'])) {
            // Handle when the delete form is submitted
            // Delete the payment from the database
            $deleteStmt = $con->prepare("DELETE FROM CATEGORY WHERE CategoryID = ?");
            $deleteStmt->execute([$_SESSION['selectedCategory']['CategoryID']]);

            $msg = '<font color = "green">Your category has been deleted.</font><br/>';
            // Clear the session variable after successful delete
               unset($_SESSION['selectedCategory']);
        }
    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Delete Category</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
        ?>
        <!-- Payment Selection Form -->
        <form method="post" action="#">
            <label for="categoryID">Select Category ID:</label>
            <select name="categoryID" id="categoryID" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['CategoryID'] ?>"><?= $category['CategoryID'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="selectCategory">Select Category</button>
        </form>

        <!-- Payment Deleting Form -->
        <form method="post" action="#" onsubmit="return confirmDelete();">
            <button type="submit" name="deleteCategory" id="deleteButton">Delete Category</button>
        </form>

        <script>
        function confirmDelete() {
            var result = confirm("Are you sure you want to delete this category?");
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