<!-- 
Name: Mallory Newberry
Created on: 09/29/23
Modified on: 10/1/23
plantsAndImagesForm.php -->

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
    <title>New Plant and/or Image</title>
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

    <!-- Plant form -->
    <div class="form_container" style="float: left; width: 45%; margin-left: 12%;">
        <div class="titlepage">
            <h1 class="h1_form">Add a New Plant</h1>
        </div>
        <form action="plantsProcess.php" method="post">
            <div class="form-group">
                <label for="name">Plant Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label></br>
                <input type="number" step="0.01" name="price" id="price" required>
            </div>
            <div class="form-group">
                <label for="stock_quantity">Quantity in Stock</label></br>
                <input type="number" name="stock_quantity" id="stock_quantity" required>
            </div>
            <div class="center">
                <button type="submit">Add Plant</button>
            </div>
        </form>
    </div>

    <!-- Image form -->
    <div class="form_container" style="float: right; width: 45%; margin-right: 12%;">
        <div class="titlepage">
            <h1 class="h1_form">Add a New Image to a Plant</h1>
        </div>
        <button type="">Choose Plant</button>

        <form action="imagesProcess.php" method="post">
            <div class="form-group">
                <label for="image">Insert Image: </label>
                <input type="file" name="image" id="image" required>
            </div>
            <div class="form-group">
                <label for="url">URL of Image: </label>
                <input type="url" name="url" id="url">
            </div>
            <div class="center">
                <button type="submit">Add Image</button>
            </div>
        </form>
    </div>

</body>
</html>