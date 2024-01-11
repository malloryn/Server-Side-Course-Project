<!-- 
Name: Mallory Newberry
Created on: 12/1/23
Modified on: 12/1/23
nurseryReport.php -->

<?php  session_start(); //this must be the very first line on the php page, to register this page to use session variables

	require_once "../dbconnect.php";

    // Grabbing data from the PLANT table
    $stmt = $con->prepare("SELECT NurseryID, COUNT(PlantID) as plant_count, SUM(StockQuantity) as total_stock FROM PLANT GROUP BY NurseryID");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare an array to store the data
    $data = array();

    // Loop through the results and store them in the array
    foreach ($result as $row) {
        $data[$row['NurseryID']] = array(
            'plant_count' => $row['plant_count'],
            'total_stock' => $row['total_stock']
        );
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
    <title>Plant Stock Report</title>
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
    <h1 class="h1_form2">Stock of Plants by Nursery</h1>
    <div class="paragraph-text">
        <p>
        This column chart shows the stock of plants from each nursery. The IDs of the nurseries are displayed on the x-axis with the Total Stock on the y-axis.
        </p>
    </div>

    <div id="chart-container">
        <canvas id="chart"></canvas>
    </div>

    <script>
        var data = <?php echo json_encode($data); ?>;

        // Generate the labels and data dynamically
        var labels = Object.keys(data);
        var chartData = labels.map(function(key) {
            return data[key].total_stock; // Use total_stock for the y-axis
        });

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
                title: 'Stock of Plants by Nursery',
                xaxisLabels: labels, 
                colors: barColor,
                labelsAbove: true,
                textSize: 12
            }
        }).draw();
    </script>
</body>
</html>