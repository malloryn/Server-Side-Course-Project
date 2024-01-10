<!-- 
Name: Mallory Newberry
Created on: 12/1/23
Modified on: 12/1/23
cannedReports.php -->

<?php  session_start(); //this must be the very first line on the php page, to register this page to use session variables

	require_once "../dbconnect.php";

    // link to the RGraph library
    echo '<script src="../RGraph/libraries/RGraph.common.core.js"></script>';
    echo '<script src="../RGraph/libraries/RGraph.bar.js"></script>';
    echo '<script src="../RGraph/libraries/RGraph.pie.js"></script>';
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canned Reports</title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../greeno/css/bootstrap.min.css">
    <!-- index.html style css -->
    <link rel="stylesheet" href="../greeno/css/style.css">
    <!-- register page css
    <link rel="stylesheet" href="../style.css"> -->
    <link rel="stylesheet" href="../greeno/css/responsive.css">
    <!-- table css -->
    <link rel="stylesheet" href="../table_style.css">
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
    <h1 class="h1_form2">Canned Reports</h1>
    <div class="paragraph-text">
        <p>
            Shown are 3 canned reports, ready to be distributed for weekly or monthly metrics. These are fixed and can't be customized or filtered.
        </p>
    </div>

    <!-- report for customers in the system with 0 orders -->
    <h2>Customers with No Placed Orders</h2>

    <?php
        // SQL query to get customers with no placed orders
        $stmt = $con->prepare("
            SELECT
                c.CustomerID,
                c.FirstName,
                c.LastName,
                c.Email,
                c.Address,
                c.Phone
            FROM
                CUSTOMER c
            LEFT JOIN
                `ORDER` o ON c.CustomerID = o.CustomerID
            WHERE
                o.OrderID IS NULL
        ");

        $stmt->execute();
        $customersWithNoOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table>
        <thead>
            <tr>
                <th>CustomerID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Loop through the results and display each row in the table
                foreach ($customersWithNoOrders as $row) {
                    echo "<tr>";
                    echo "<td>{$row['CustomerID']}</td>";
                    echo "<td>{$row['FirstName']}</td>";
                    echo "<td>{$row['LastName']}</td>";
                    echo "<td>{$row['Email']}</td>";
                    echo "<td>{$row['Address']}</td>";
                    echo "<td>{$row['Phone']}</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>

    <!-- report for plants with the most reviews -->
    <h2>Plants Ordered by the Most Reviews</h2>

    <?php
        // SQL query to get the most reviewed plants
        $stmt = $con->prepare("
            SELECT
                p.PlantID,
                p.Name AS PlantName,
                COUNT(r.ReviewID) AS ReviewCount,
                AVG(r.Rating) AS AverageRating
            FROM
                PLANT p
            LEFT JOIN
                REVIEW r ON p.PlantID = r.PlantID
            GROUP BY
                p.PlantID, p.Name
            ORDER BY
                ReviewCount DESC
        ");

        $stmt->execute();
        $plantReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table>
    <thead>
        <tr>
            <th>Rank</th>
            <th>PlantID</th>
            <th>Plant Name</th>
            <th>Review Count</th>
            <th>Average Rating</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Loop through the results and display each row in the table
        $rank = 1;
        foreach ($plantReviews as $row) {
            echo "<tr>";
            echo "<td>{$rank}</td>";
            echo "<td>{$row['PlantID']}</td>";
            echo "<td>{$row['PlantName']}</td>";
            echo "<td>{$row['ReviewCount']}</td>";
            echo "<td>{$row['AverageRating']}</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </tbody>
    </table>

    <!-- report for amount of plants in stock in pie chart -->
    <h2>Total Number of Plants as a Pie Chart</h2>

    <?php
        // grabbing data from the PLANT table
        $stmt = $con->prepare("SELECT Name, SUM(StockQuantity) as total_stock FROM PLANT GROUP BY Name");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // prepare an array to store the data
        $data = array();
        // Loop through the results and store them in the array
        foreach ($result as $row) {
            $data[$row['Name']] = $row['total_stock'];
        }
    ?>

    <div id="chart-container-can" style="width: 3000px; height: 3000px;">
        <canvas id="chart1"></canvas>
    </div>

    <script>
        var data = <?php echo json_encode($data); ?>;

        // Generate the labels and data dynamically
        var labels = Object.keys(data);
        var chartData = Object.values(data);

        // build the pie chart
        new RGraph.Pie({
            id: 'chart1',
            data: chartData, 
            options: {
                labels: labels, 
                colors: ['red', 'blue', 'green', 'yellow', 'orange', 'purple', 'pink', 'brown', 'cyan'],
                labelsSticks: true,
                labelsSticksLength: 20,
                labelsCenter: true,
                labelsCenterSize: 8,
                textSize: 8
            }
        }).draw();
    </script>
</body>
</html>