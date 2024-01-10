<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/11/23
editCategory.php -->

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
    <title>Edit Categories</title>
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
       $categoryStmt = $con->prepare("SELECT CategoryID FROM CATEGORY");
       $categoryStmt->execute();
       $categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
   
       // Initialize variables to store the selected admin data
       $selectedCategory = "";
   
       if (isset($_POST['selectCategory'])) {
           // Handle when a admin is selected
           $selectedCategoryID = $_POST['categoryID'];
   
           // Fetch the admin data based on the selected ID
           $categoryDataStmt = $con->prepare("SELECT * FROM CATEGORY WHERE CategoryID = ?");
           $categoryDataStmt->execute([$selectedCategoryID]);
           $selectedCategory = $categoryDataStmt->fetch(PDO::FETCH_ASSOC);
   
           // Store the selected admin's data in a session variable
           $_SESSION['selectedCategory'] = $selectedCategory;
       }

        if (isset($_POST['editCategory'])&& is_array($_SESSION['selectedCategory'])) {
            // Handle when the edit form is submitted
            $editedData = [
                'name' => $_POST['name'],
            ];
    
            // Update the admin data in the database
            $updateStmt = $con->prepare("UPDATE CATEGORY SET Name = ? WHERE CategoryID = ?");
            $updateStmt->execute([$editedData['name'], $_SESSION['selectedCategory']['CategoryID']]);
    
            $msg = '<font color = "green">Your category has been edited and saved.</font><br/>';
            // Clear the session variable after successful update
            unset($_SESSION['selectedCategory']);
        }

    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Edit Category</h1>
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <!-- Category Selection Form -->
        <form method="post" action="#">
            <label for="categoryID">Select Category ID:</label>
            <select name="categoryID" id="categoryID" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['CategoryID'] ?>"><?= $category['CategoryID'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="selectCategory">Select Category</button>
        </form>

        <!-- Category Editing Form -->
        <form method="post" action="#">
            <label for="name">Category Name:</label>
            <input type="text" name="name" value="<?php echo isset($_SESSION['selectedCategory']) ? $_SESSION['selectedCategory']['Name'] : ''; ?>" required>

            <button type="submit" name="editCategory">Confirm</button>
        </form>
    </div>
</body>
</html>