<!-- 
Name: Mallory Newberry
Created on: 11/09/23
Modified on: 11/10/23
logout.php -->

<?php  session_start(); //this must be the very first line on the php page, to register this page to use session variables
	session_destroy();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">

<head>
        <title>Logout</title>
        <!-- bootstrap css -->
        <link rel="stylesheet" href="../greeno/css/bootstrap.min.css">
        <!-- index.html style css -->
        <link rel="stylesheet" href="../greeno/css/style.css">
        <!-- page css -->
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="../greeno/css/responsive.css">
</head>
<body>

<?php
        // shows a pop-up to the user and sends them back to the login page
        echo "<script>alert('Thank you for visiting!'); window.location.href='login.php';</script>";  
        return;
?>
</body>
</html>