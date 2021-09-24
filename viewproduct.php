<!DOCTYPE html>
<html lang="en">
<head>
<title>View Products</title>

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
    <h1>Displaying All Products</h1>
    <?php
        require_once ("./includes/db.inc.php");

        $query="select ProductID, ProductName, Category, Price, Comments, Stock from products;";
        $result=mysqli_query($connection, $query);
        if (!$result) {
            echo "<p>Something is wrong with " , $query, "</p>";
        }
        else {
            echo "<table border='1'>\n";
            echo "<tr><th>ProductID</th><th>ProductName</th><th>Category</th><th>Price</th><th>Comments</th><th>Stock</th></tr>\n";

            while ($row=mysqli_fetch_assoc($result)){
                echo "<tr><td>", $row["ProductID"], "</td>\n";
                echo "<td>", $row["ProductName"], "</td>\n";
                echo "<td>", $row["Category"], "</td>\n";
                echo "<td>", $row["Price"], "</td>\n";
                echo "<td>", $row["Comments"], "</td>\n";
                echo "<td>", $row["Stock"], "</td></tr>\n";
            }
            echo "</table>\n<br>";
            mysqli_free_result($result);
        }
        
        mysqli_close($connection);
    ?>

    <button type="button" onclick="tableToCSV()">
        Download
    </button>

    <script type="text/javascript">
        function tableToCSV() {
            var csv_data = [];
            var rows = document.getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var cols = rows[i].querySelectorAll('td,th');
                var csvrow = [];

                for (var j = 0; j < cols.length; j++) {
                    csvrow.push(cols[j].innerHTML);
                }
                csv_data.push(csvrow.join(","));
            }
            csv_data = csv_data.join('\n');
            downloadCSVFile(csv_data);
        }
 
        function downloadCSVFile(csv_data) {

            CSVFile = new Blob([csv_data], {
                type: "text/csv"
            });
 
            var temp_link = document.createElement('a');
 
            temp_link.download = "Products.csv";
            var url = window.URL.createObjectURL(CSVFile);
            temp_link.href = url;
 
            temp_link.style.display = "none";
            document.body.appendChild(temp_link);

            temp_link.click();
            document.body.removeChild(temp_link);
        }
    </script>

    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>
