<!-- 
Name: Mallory Newberry
Created on: 09/29/23
Modified on: 10/1/23
reviewsForm.php -->

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
    <title>Review</title>
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

    <?php
        $stmt = $con->prepare("Select * from CUSTOMER where email = ?");
        $stmt->execute([$_SESSION['email']]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        // Convert the customer object into an array
        $customer = get_object_vars($row);

        // Store the customer data in a session variable
        $_SESSION['customer'] = $customer;

        // Initializing variables
        $plantId = "";
        $rating = "";
        $comment = "";

        function sanitize_input($input) {
            return htmlspecialchars(trim($input));
        }

        $plantId = sanitize_input(filter_input(INPUT_POST, 'plant'));
        $rating = sanitize_input(filter_input(INPUT_POST, 'rating'));
        $comment = sanitize_input(filter_input(INPUT_POST, 'comment'));

        if ($plantId !== "" && $rating !== "" && $comment !== "") {
            // Insert the CustomerID from the $customer array into the CustomerID column
            $customerId = $customer['CustomerID'];
        
            //enter data into the database
            $stmt = $con->prepare("insert into REVIEW values(null, ?, ?, ?, ?)");
            if ($stmt->execute(array($plantId, $customerId, $rating, $comment))==TRUE)
                $msg = '<font color = "green">Your review has been added!</font><br/>';
            else $msg = "Your information cannot be entered this time. Please try again later.";
        }

        // Fetch plant names from the database
        $plantStmt = $con->prepare("SELECT PlantID, Name FROM PLANT");
        $plantStmt->execute();
        $plants = $plantStmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="form_container">
        <div class="titlepage">
            <h1 class="h1_form">Leave a Review</h1>
        </div>
        <div class="normal_text">
            <a href="viewPastReviews.php">View Your Past Reviews</a> 
        </div>
        <?php 
            print $msg;
            $msg = "";
		?>
        <form action="#" method="post">
            <div class="form-group">
                <label for="plant">Choose Plant by Name:</label><br>
                <select name="plant" id="plant" required>
                    <?php foreach ($plants as $plant): ?>
                        <option value="<?= $plant['PlantID'] ?>"><?= $plant['Name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rating">Give a Star Rating:</label></br>
                <fieldset class="rating">
                    <input type="radio" id="star5" name="rating" value="5"><label for="star5"></label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4"></label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3"></label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2"></label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1"></label>
                </fieldset>
            </div>
            <div class="form-group">
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment"></textarea>
            </div>
            <div class="center">
                <button type="submit">Add Review</button>
            </div>
        </form>
    </div>
</body>
</html>