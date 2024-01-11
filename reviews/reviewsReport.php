<!-- 
Name: Mallory Newberry
Created on: 12/1/23
Modified on: 12/1/23
reviewsReport.php -->

<?php  session_start(); //this must be the very first line on the php page, to register this page to use session variables

	require_once "../dbconnect.php";

    // grabbing data from the REVIEW table
    // this will be used to see the average rating of a plant
    $stmt = $con->prepare("SELECT PlantID, AVG(Rating) as avg_rating FROM REVIEW GROUP BY PlantID");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // prepare an array to store the data
    $data = array();
    // Loop through the results and store them in the array
    foreach ($result as $row) {
    $data[$row['PlantID']] = $row['avg_rating'];
    }
    // link to the RGraph library
    echo '<script src="../RGraph/libraries/RGraph.common.core.js"></script>';
    echo '<script src="../RGraph/libraries/RGraph.bar.js"></script>';
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ratings Report</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../greeno/css/bootstrap.min.css">
    <!-- index.html style css -->
    <link rel="stylesheet" href="../greeno/css/style.css">
    <!-- register page css
    <link rel="stylesheet" href="../style.css"> -->
    <link rel="stylesheet" href="../greeno/css/responsive.css">
    <!-- report css -->
    <link rel="stylesheet" href="../reportStyle.css">
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
    <h1 class="h1_form2">Average Ratings of Plants</h1>
    <div class="paragraph-text">
        <p>
        This column chart shows the average ratings of the plants for sale. The IDs of the plants are displayed on the x-axis with the ratings 0-5 on the y-axis. 
        </p>
    </div>

    <div id="chart-container">
        <canvas id="chart"></canvas>
    </div>

    <script>
        var data = <?php echo json_encode($data); ?>;

        // Generate the labels and data dynamically
        var labels = Object.keys(data).map(function(key) {
            return key;
        });
        var chartData = Object.values(data);

        // Calculate the width and height of the chart
        var barColor = ['green'];
        var chartWidth = window.innerWidth / chartData.length;
        var chartHeight = window.innerHeight / chartData.length;

        // Set the width and height of the chart container
        document.getElementById('chart-container').style.width = chartWidth + 'px';
        document.getElementById('chart-container').style.height = chartHeight + 'px';

        // build the column chart
        new RGraph.Bar({
            id: 'chart',
            data: [chartData], 
            options: {
                title: 'Average Ratings by Plant',
                xaxisLabels: labels, 
                yaxisLabels: ['0', '1', '2', '3', '4', '5'],
                colors: barColor,
                labelsAbove: true,
                textSize: 12
            }
        }).draw();
    </script>
</body>
</html>