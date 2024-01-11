<!-- 
Name: Mallory Newberry
Created on: 12/1/23
Modified on: 12/1/23
viewPastReviews.php -->

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
    <title>Review History</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../greeno/css/bootstrap.min.css">
    <!-- index.html style css -->
    <link rel="stylesheet" href="../greeno/css/style.css">
    <!-- page css -->
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
                                <li class="active"> <a href="../greeno/#">Home</a> </li>
                                <li><a href="../greeno/#plant">Plants</a></li>
                                <li><a href="../greeno/#gallery">Showcase</a></li>
                                <li><a href="../customer/customerPortal.html">Back to Customer Page</a></li>
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
    <div>
        <h1>Your Review History</h1>
    </div>
    <div class="table-container">
        <?php
            $stmt = $con->prepare("select * from CUSTOMER where email = ?");
            $stmt->execute([$_SESSION['email']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            
            // Get the CustomerID for the current user
            $customerId = $row['CustomerID'];

            print '<table>';
            print '<tr class="darker_header"><td>Review ID</td><td>Plant Name</td><td>Rating</td><td>Comment</td></tr>';
            // sends query to database
            $stmt = $con->prepare("SELECT REVIEW.*, PLANT.Name FROM REVIEW INNER JOIN PLANT ON REVIEW.PlantID = PLANT.PlantID WHERE REVIEW.CustomerID = ?");

            // Bind the parameter for CustomerID
            $stmt->bindParam(1, $customerId, PDO::PARAM_INT);
            
            // execute before fetching results
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                print '<tr>';
                print '<td>'.$row["ReviewID"]."</td><td>".$row["Name"]."</td><td>".$row["Rating"]."</td><td>".$row["Comment"]."</td>";
                print '</tr>';
            }

            print '</table>';
            $stmt->closeCursor();
        ?>
    </div>
</body>
</html>