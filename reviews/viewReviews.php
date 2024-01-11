<!-- 
Name: Mallory Newberry
Created on: 11/10/23
Modified on: 11/10/23
viewReviews.php -->

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
    <title>View Reviews</title>
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
    <div>
        <h1>Reviews from Customers</h1>
    </div>
    <div class="center">
        <a href="reviewsReport.php">See Average Ratings For Plants Report</a> 
    </div>
    <!-- Add download button -->
    <div class="center">
        <a href="#" id="download-csv">Download CSV of this table</a>
    </div>
    <div class="table-container">
        <?php
            // Fetch data from the database
            $stmt = $con->prepare("SELECT * FROM REVIEW");
            $stmt->execute();

            // Fetch all rows
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Close the cursor
            $stmt->closeCursor();

            // Output table
            if (!empty($rows)) {
                echo '<table>';
                echo '<tr class="darker_header"><td>Review ID</td><td>Plant ID</td><td>Customer ID</td><td>Rating (in Stars)</td><td>Comment</td></tr>';

                foreach ($rows as $row) {
                    echo '<tr>';
                    echo '<td>' . $row["ReviewID"] . '</td><td>' . $row["PlantID"] . '</td><td>' . $row["CustomerID"] . '</td><td>' . $row["Rating"] . '</td><td>' . $row["Comment"] . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            }
        ?>

        <script>
            // Function to convert array to CSV
            function arrayToCSV(data) {
                const csvContent = "data:text/csv;charset=utf-8,"
                    + data.map(row => Object.values(row).join(',')).join('\n');
                return encodeURI(csvContent);
            }

            // Get the table data
            const tableData = <?php echo json_encode($rows); ?>;

            // Add event listener to the download button
            document.getElementById('download-csv').addEventListener('click', function () {
                const csvData = arrayToCSV(tableData);
                const downloadLink = document.createElement('a');
                downloadLink.href = csvData;
                downloadLink.download = 'review_data.csv';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            });
        </script>
    </div>
</body>
</html>