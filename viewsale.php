<!DOCTYPE html>
<html lang="en">
<head>
<title>View Sales</title>

<meta charset="utf-8" />
<meta name="description" content="Product form"  />
<meta name="keywords" content="Form, Input" />
<link href= "styles/style.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
<script src="js/scripts.js"></script>

</head>
<body>
    <?php
        include "./includes/header.inc";
        include "./includes/nav.inc";
    ?>
    <h1>Displaying All Sales</h1>
    <?php
        require_once ("./includes/db.inc.php");

        $query="select SaleID, SaleDate, NumberofProducts, PriceTotal, EmployeeID from sales;";
        $result=mysqli_query($connection, $query);
        if (!$result) {
            echo "<p>Something is wrong with " , $query, "</p>";
        }
        else {
            echo "<table border='1'>\n";
            echo "<tr><th>SaleID</th><th>SaleDate</th><th>NumberofProducts</th><th>PriceTotal</th><th>EmployeeID</th></tr>\n";

            while ($row=mysqli_fetch_assoc($result)){
                echo "<tr><td>", $row["SaleID"], "</td>\n";
                echo "<td>", $row["SaleDate"], "</td>\n";
                echo "<td>", $row["NumberofProducts"], "</td>\n";
                echo "<td>", $row["PriceTotal"], "</td>\n";
                echo "<td>", $row["EmployeeID"], "</td>\n";
            }
            echo "</table>\n<br>";
            mysqli_free_result($result);
        }
        
        mysqli_close($connection);
    ?>
    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>