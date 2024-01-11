<!-- 
Name: Mallory Newberry
Created on: 09/29/23
Modified on: 10/1/23
plantsProcess.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Information</title>
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
    <div class="form_container">
        <div class="titlepage">
            <h1>New Plant Information</h1>
        </div>
        <?php
            // Initializing variables
            $name = "";
            $description = "";
            $price = "";
            $stock_quantity = "";

            function sanitize_input($input) {
                return htmlspecialchars(trim($input));
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = sanitize_input(filter_input(INPUT_POST, 'name'));
                $description = sanitize_input(filter_input(INPUT_POST, 'description'));
                $price = sanitize_input(filter_input(INPUT_POST, 'price'));
                $stock_quantity = sanitize_input(filter_input(INPUT_POST, 'stock_quantity'));

                echo "<p style='color: green; text-align: center;'>Your plant has been added!</p></br>";
                echo "<p>Plant Name: $name</p>";
                echo "<p>Description: $description</p>";
                echo "<p>Price: $$price</p>";
                echo "<p>Stock Quantity: $stock_quantity</p>";
            }
        ?>
    </div>
</body>
</html>