<?php
  session_start();
  if (!isset($_SESSION["Login"]) || !isset($_SESSION["Role"])) {
    header ("location: logout.php");
    exit;
  }
  else {
    if ($_SESSION["Login"]!=true || $_SESSION["Role"]!="manager") {
      header ("location: logout.php");
      exit;
    }
    else {
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="description" content="Digital Sales System" />
  <meta name="keywords" content="Sales, System" />
  <meta name="author" content="Team Timezone Differences"  />
  <title>Sales Summary</title>
  <link href= "styles/style.css" rel="stylesheet"/>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
<script src="js/scripts.js"></script>
  <!-- <script src="scripts/seminarinput.js"></script> -->
</head>

<body>
  <?php
      include "./includes/header.inc";
      include "./includes/nav.inc";

      require_once ("./includes/db.inc.php");

      do {
        if (isset($_POST["submit"])) {
          $time_period = $_POST["time_period"];
          if ($time_period == "monthly") {
              $year = date("Y");
              $month = date('m') -1;
              $rangemonth = $month - 2;
              $value = $month;
              //previous group for chart
              $query="SELECT SaleID, SaleDateTime, SUM(PriceTotal) AS PriceTotal, EmployeeID, COUNT(*) AS SaleNumber
              FROM sales
              WHERE YEAR(SaleDateTime)='$year' AND MONTH(SaleDateTime) BETWEEN '$rangemonth' AND '$month' GROUP BY MONTH(SaleDateTime);";
              $result=mysqli_query($connection, $query);
              if (!$result) {
                  echo "<p>Something is wrong with " , $query, "</p>";
              }
              else {
                  $chart_data = '';
                  $exploded_month = '';
                  $realmonth = '';
                  while ($row= mysqli_fetch_array($result)){
                      $exploded_month = explode("-",$row["SaleDateTime"]);
                      $realmonth = $exploded_month[1];
                      $chart_data .= "{ month:'".$year."-".$realmonth."', revenue:".$row["PriceTotal"].", sale:".$row["SaleNumber"]."}, "; 
                  }
                  $chart_data = substr($chart_data, 0, -2);
                  mysqli_free_result($result);
              }
              //previous months
              $query="SELECT SaleID, SaleDateTime, PriceTotal, EmployeeID 
              FROM sales
              WHERE YEAR(SaleDateTime)='$year' AND MONTH(SaleDateTime) BETWEEN '$rangemonth' AND '$month';";
              $result=mysqli_query($connection, $query);
              if (!$result) {
                  echo "<p>Something is wrong with " , $query, "</p>";
              }
              else {
                  $i = 0;
                  while ($row=mysqli_fetch_assoc($result)){
                    $sales[$i]["SaleID"] = $row["SaleID"];
                    $sales[$i]["SaleDateTime"] = $row["SaleDateTime"];
                    $sales[$i]["PriceTotal"] = $row["PriceTotal"];
                    $sales[$i]["EmployeeID"] = $row["EmployeeID"];
                    $i++;
                }
                mysqli_free_result($result);
              }            
  
            $query="SELECT ProductID, ProductName, Category, Price, Comments, Stock FROM products;";
            $result=mysqli_query($connection, $query);
            if (!$result) {
                echo "<p>Something is wrong with " , $query, "</p>";
            }
            else {
                $i = 0;
                while ($row=mysqli_fetch_assoc($result)){
                    $products[$i]["ProductID"] = $row["ProductID"];
                    $products[$i]["ProductName"] = $row["ProductName"];
                    $products[$i]["Category"] = $row["Category"];
                    $products[$i]["Price"] = $row["Price"];
                    $products[$i]["Comments"] = $row["Comments"];
                    $products[$i]["Stock"] = $row["Stock"];
                    $i++;
                }
                mysqli_free_result($result);
            }
    
            $query="SELECT SaleID, ProductID, Quantity, SubTotal from productsalelinks;";
            $result=mysqli_query($connection, $query);
            if (!$result) {
                echo "<p>Something is wrong with " , $query, "</p>";
            }
            else {
                if (isset($sales)) {
                    foreach ($sales as $sale) {
                    $saleIDs[] = $sale["SaleID"];
                    }
                } else $saleIDs = [];
                $i = 0;
                while ($row=mysqli_fetch_assoc($result)){
                    if (in_array($row["SaleID"], $saleIDs)) {
                    $saledetails[$i]["SaleID"] = $row["SaleID"];
                    $saledetails[$i]["ProductID"] = $row["ProductID"];
                    $saledetails[$i]["Quantity"] = $row["Quantity"];
                    $saledetails[$i]["SubTotal"] = $row["SubTotal"];
                    $i++;
                    }
                }
                mysqli_free_result($result);
            }
            //currentmonth
            $month = $month + 1;
            $query="SELECT SaleID, SaleDateTime, PriceTotal, EmployeeID 
              FROM sales
              WHERE YEAR(SaleDateTime)='$year' AND MONTH(SaleDateTime)='$month';";
              $result=mysqli_query($connection, $query);
              if (!$result) {
                  echo "<p>Something is wrong with " , $query, "</p>";
              }
              else {
                  $i = 0;
                  while ($row=mysqli_fetch_assoc($result)){
                    $cursales[$i]["SaleID"] = $row["SaleID"];
                    $cursales[$i]["SaleDateTime"] = $row["SaleDateTime"];
                    $cursales[$i]["PriceTotal"] = $row["PriceTotal"];
                    $cursales[$i]["EmployeeID"] = $row["EmployeeID"];
                    $i++;
                }
                mysqli_free_result($result);
              }            
  
            $query="SELECT ProductID, ProductName, Category, Price, Comments, Stock FROM products;";
            $result=mysqli_query($connection, $query);
            if (!$result) {
                echo "<p>Something is wrong with " , $query, "</p>";
            }
            else {
                $i = 0;
                while ($row=mysqli_fetch_assoc($result)){
                    $curproducts[$i]["ProductID"] = $row["ProductID"];
                    $curproducts[$i]["ProductName"] = $row["ProductName"];
                    $curproducts[$i]["Category"] = $row["Category"];
                    $curproducts[$i]["Price"] = $row["Price"];
                    $curproducts[$i]["Comments"] = $row["Comments"];
                    $curproducts[$i]["Stock"] = $row["Stock"];
                    $i++;
                }
                mysqli_free_result($result);
            }
    
            $query="SELECT SaleID, ProductID, Quantity, SubTotal from productsalelinks;";
            $result=mysqli_query($connection, $query);
            if (!$result) {
                echo "<p>Something is wrong with " , $query, "</p>";
            }
            else {
                if (isset($cursales)) {
                    foreach ($cursales as $cursale) {
                    $cursaleIDs[] = $cursale["SaleID"];
                    }
                } else $cursaleIDs = [];
                $i = 0;
                while ($row=mysqli_fetch_assoc($result)){
                    if (in_array($row["SaleID"], $cursaleIDs)) {
                    $cursaledetails[$i]["SaleID"] = $row["SaleID"];
                    $cursaledetails[$i]["ProductID"] = $row["ProductID"];
                    $cursaledetails[$i]["Quantity"] = $row["Quantity"];
                    $cursaledetails[$i]["SubTotal"] = $row["SubTotal"];
                    $i++;
                    }
                }
                mysqli_free_result($result);
            }
          }
          else if ($time_period == "weekly") {
              $week = date("W") - 1;
              $year = date("Y");
              $value = $week;
              $rangeweek = $week - 2;
              $query="SELECT SaleID, SaleDateTime, SUM(PriceTotal) AS PriceTotal, EmployeeID, COUNT(*) AS SaleNumber
              FROM sales
              WHERE YEAR(SaleDateTime)='$year' AND WEEK(SaleDateTime,3) BETWEEN '$rangeweek' AND '$week' GROUP BY WEEK(SaleDateTime,3);";
              $result=mysqli_query($connection, $query);
              if (!$result) {
                  echo "<p>Something is wrong with " , $query, "</p>";
              }
              else {
                  $chart_data = '';
                  $exploded_week = '';
                  $realweek = '';
                  while ($row= mysqli_fetch_array($result)){
                      $realweek = date("W", strtotime($row["SaleDateTime"]));
                      $chart_data .= "{ w:'"."Week ".$realweek."', revenue:".$row["PriceTotal"].", sale:".$row["SaleNumber"]."}, "; 
                  }
                  $chart_data = substr($chart_data, 0, -2);
                  mysqli_free_result($result);
              }
              //previous weeks
              $query="SELECT SaleID, SaleDateTime, PriceTotal, EmployeeID 
              FROM sales
              WHERE YEAR(SaleDateTime)='$year' AND WEEK(SaleDateTime,3) BETWEEN '$rangeweek' AND '$week';";
              $result=mysqli_query($connection, $query);
              if (!$result) {
                  echo "<p>Something is wrong with " , $query, "</p>";
              }
              else {
                  $i = 0;
                  while ($row=mysqli_fetch_assoc($result)){
                    $sales[$i]["SaleID"] = $row["SaleID"];
                    $sales[$i]["SaleDateTime"] = $row["SaleDateTime"];
                    $sales[$i]["PriceTotal"] = $row["PriceTotal"];
                    $sales[$i]["EmployeeID"] = $row["EmployeeID"];
                    $i++;
                }
                mysqli_free_result($result);
              }            
  
            $query="SELECT ProductID, ProductName, Category, Price, Comments, Stock FROM products;";
            $result=mysqli_query($connection, $query);
            if (!$result) {
                echo "<p>Something is wrong with " , $query, "</p>";
            }
            else {
                $i = 0;
                while ($row=mysqli_fetch_assoc($result)){
                    $products[$i]["ProductID"] = $row["ProductID"];
                    $products[$i]["ProductName"] = $row["ProductName"];
                    $products[$i]["Category"] = $row["Category"];
                    $products[$i]["Price"] = $row["Price"];
                    $products[$i]["Comments"] = $row["Comments"];
                    $products[$i]["Stock"] = $row["Stock"];
                    $i++;
                }
                mysqli_free_result($result);
            }
    
            $query="SELECT SaleID, ProductID, Quantity, SubTotal from productsalelinks;";
            $result=mysqli_query($connection, $query);
            if (!$result) {
                echo "<p>Something is wrong with " , $query, "</p>";
            }
            else {
                if (isset($sales)) {
                    foreach ($sales as $sale) {
                    $saleIDs[] = $sale["SaleID"];
                    }
                } else $saleIDs = [];
                $i = 0;
                while ($row=mysqli_fetch_assoc($result)){
                    if (in_array($row["SaleID"], $saleIDs)) {
                    $saledetails[$i]["SaleID"] = $row["SaleID"];
                    $saledetails[$i]["ProductID"] = $row["ProductID"];
                    $saledetails[$i]["Quantity"] = $row["Quantity"];
                    $saledetails[$i]["SubTotal"] = $row["SubTotal"];
                    $i++;
                    }
                }
                mysqli_free_result($result);
            }
            //currentweek
            $week = $week + 1;
            $query="SELECT SaleID, SaleDateTime, PriceTotal, EmployeeID 
              FROM sales
              WHERE YEAR(SaleDateTime)='$year' AND WEEK(SaleDateTime,3)='$week';";
              $result=mysqli_query($connection, $query);
              if (!$result) {
                  echo "<p>Something is wrong with " , $query, "</p>";
              }
              else {
                  $i = 0;
                  while ($row=mysqli_fetch_assoc($result)){
                    $cursales[$i]["SaleID"] = $row["SaleID"];
                    $cursales[$i]["SaleDateTime"] = $row["SaleDateTime"];
                    $cursales[$i]["PriceTotal"] = $row["PriceTotal"];
                    $cursales[$i]["EmployeeID"] = $row["EmployeeID"];
                    $i++;
                }
                mysqli_free_result($result);
              }            
  
            $query="SELECT ProductID, ProductName, Category, Price, Comments, Stock FROM products;";
            $result=mysqli_query($connection, $query);
            if (!$result) {
                echo "<p>Something is wrong with " , $query, "</p>";
            }
            else {
                $i = 0;
                while ($row=mysqli_fetch_assoc($result)){
                    $curproducts[$i]["ProductID"] = $row["ProductID"];
                    $curproducts[$i]["ProductName"] = $row["ProductName"];
                    $curproducts[$i]["Category"] = $row["Category"];
                    $curproducts[$i]["Price"] = $row["Price"];
                    $curproducts[$i]["Comments"] = $row["Comments"];
                    $curproducts[$i]["Stock"] = $row["Stock"];
                    $i++;
                }
                mysqli_free_result($result);
            }
    
            $query="SELECT SaleID, ProductID, Quantity, SubTotal from productsalelinks;";
            $result=mysqli_query($connection, $query);
            if (!$result) {
                echo "<p>Something is wrong with " , $query, "</p>";
            }
            else {
                if (isset($cursales)) {
                    foreach ($cursales as $cursale) {
                    $cursaleIDs[] = $cursale["SaleID"];
                    }
                } else $cursaleIDs = [];
                $i = 0;
                while ($row=mysqli_fetch_assoc($result)){
                    if (in_array($row["SaleID"], $cursaleIDs)) {
                    $cursaledetails[$i]["SaleID"] = $row["SaleID"];
                    $cursaledetails[$i]["ProductID"] = $row["ProductID"];
                    $cursaledetails[$i]["Quantity"] = $row["Quantity"];
                    $cursaledetails[$i]["SubTotal"] = $row["SubTotal"];
                    $i++;
                    }
                }
                mysqli_free_result($result);
            }
              /*
              A week, according to html input type 'week', starts with Monday.
              A starting week of a year must have 4 or more days.
              Therefore, week mode for SQL is 3
              */
          }
          
            
      }} while (0);
        mysqli_close($connection);
  ?>
  <h1>Prediction Summary</h1>
  <form method="post" action="" id="new_sale">
    <fieldset class="salesum">
      <legend>Prediction Control</legend>
      <p class="row">
        <label for="time_period">Time Period</label>
        <select id="time_period" name="time_period">
            <option value="">Please select</option>
          <option value="monthly">Monthly</option>
          <option value="weekly">Weekly</option>
        </select>
      </p>
      <p>	
        <input type="submit" id="submit" name="submit" value="Confirm"/>
      </p>
    </fieldset>
  </form>
  <article class="art1">
  <?php
      if (isset($sales)) {
        $number_of_sales = round(count($sales)/3);
        $cur_number_of_sales = count($cursales);
        
        //echo "Number of Sales: $number_of_sales\n<br>";

        $net_revenue = 0;
        foreach($sales as $sale) {
          $net_revenue += round(floatval($sale["PriceTotal"])/3,2);
        }
        $cur_net_revenue = 0;
        foreach($cursales as $cursale) {
            $cur_net_revenue += floatval($cursale["PriceTotal"]);
        }
        //echo "Net Revenue: $net_revenue\n<br>";

        $number_of_products_sold = 0;
        foreach($saledetails as $saledetail) {
          $number_of_products_sold += intval($saledetail["Quantity"]);
        }
        $number_of_products_sold = round($number_of_products_sold/3);

        $cur_number_of_products_sold = 0;
        foreach($cursaledetails as $cursaledetail) {
            $cur_number_of_products_sold += intval($cursaledetail["Quantity"]);
          }
        //echo "Number of Products Sold: $number_of_products_sold\n<br>";
        if ($time_period == "monthly") {
            $temp = "month";
            $daily_number_of_sales = round($number_of_sales/30);
            $daily_net_revenue = round($net_revenue/30,2);
            $daily_number_of_products_sold = round($number_of_products_sold/30);
            $datetoday = date('d');
            $cur_daily_number_of_sales = round($cur_number_of_sales/$datetoday);
            $cur_daily_net_revenue = round($cur_net_revenue/$datetoday,2);
            $cur_daily_number_of_products_sold = round($cur_number_of_products_sold/$datetoday);
            if ($daily_number_of_sales > $cur_daily_number_of_sales){
                $forecast_number_of_sales = round($number_of_sales * 0.95);
            } else {
                $forecast_number_of_sales = round($number_of_sales * 1.05);
            }

            if ($daily_net_revenue > $cur_daily_net_revenue){
                $forecast_net_revenue = round($net_revenue * 0.95, 2);
            } else {
                $forecast_net_revenue = round($net_revenue * 1.05, 2);
            }
            if ($daily_number_of_products_sold > $cur_number_of_products_sold){
                $forecast_number_of_products_sold = round($number_of_products_sold * 0.95);
            } else {
                $forecast_number_of_products_sold = round($number_of_products_sold * 1.05);
            }
            echo "<h2>Sale summary for $rangemonth-$value/$year</h2>\n";
            echo "<div id='monthlychart'></div>";
            echo "<button type='button' onclick='generateMonthlyGraph()' class='butt3'>Graph</button>";
            echo "<h3>Prediction for $month/$year:</h3>";
            echo "<table border='1'>\n";
            echo "<tr><td></td><th>Monthly Average for $rangemonth-$value/$year</th><th>Daily Average for $rangemonth-$value/$year</th><th>Daily Average for $month/$year</th><th>Forecast for $month/$year</th></tr>\n";
            echo "<tr><th>Number of Sales</th><td>$number_of_sales</td><td>$daily_number_of_sales</td><td>$cur_daily_number_of_sales</td><td>$forecast_number_of_sales</td></tr>\n";
            echo "<tr><th>Net Revenue</th><td>$net_revenue</td><td>$daily_net_revenue</td><td>$cur_daily_net_revenue</td><td>$forecast_net_revenue</td></tr>\n";
            echo "<tr><th>Number of Products Sold</th><td>$number_of_products_sold</td><td>$daily_number_of_products_sold</td><td>$cur_daily_number_of_products_sold</td><td>$forecast_number_of_products_sold</td></tr>\n";
            echo "</table>";
            echo "<smalltext>Generate through <strong>Historical Forecasting</strong> by comparing daily average: If daily average for this week is higher than previous, +5% on previous weekly average</smalltext>\n<br/><br/>";
        }
        else {
            $temp = "week";
            $daily_number_of_sales = round($number_of_sales/7);
            $daily_net_revenue = round($net_revenue/7,2);
            $daily_number_of_products_sold = round($number_of_products_sold/7);
            $datetoday = date('w');
            $cur_daily_number_of_sales = round($cur_number_of_sales/$datetoday);
            $cur_daily_net_revenue = round($cur_net_revenue/$datetoday,2);
            $cur_daily_number_of_products_sold = round($cur_number_of_products_sold/$datetoday);
            if ($daily_number_of_sales > $cur_daily_number_of_sales){
                $forecast_number_of_sales = round($number_of_sales * 0.95);
            } else {
                $forecast_number_of_sales = round($number_of_sales * 1.05);
            }

            if ($daily_net_revenue > $cur_daily_net_revenue){
                $forecast_net_revenue = round($net_revenue * 0.95, 2);
            } else {
                $forecast_net_revenue = round($net_revenue * 1.05, 2);
            }
            if ($daily_number_of_products_sold > $cur_number_of_products_sold){
                $forecast_number_of_products_sold = round($number_of_products_sold * 0.95);
            } else {
                $forecast_number_of_products_sold = round($number_of_products_sold * 1.05);
            }
            echo "<h2>Sale summary for Week $rangeweek-$value/$year</h2>\n";
            echo "<div id='weeklychart'></div>";
            echo '<button type="button" onclick="generateWeeklyGraph()" class="butt3">Graph</button>';
            echo "<h3>Prediction for Week $week/$year:</h3>";
            echo "<table border='1'>\n";
            echo "<tr><td></td><th>Weekly Average for Week $rangeweek-$value/$year</th><th>Daily Average for Week $rangeweek-$value/$year</th><th>Daily Average for Week $week/$year</th><th>Forecast for Week $week/$year</th></tr>\n";
            echo "<tr><th>Number of Sales</th><td>$number_of_sales</td><td>$daily_number_of_sales</td><td>$cur_daily_number_of_sales</td><td>$forecast_number_of_sales</td></tr>\n";
            echo "<tr><th>Net Revenue</th><td>$net_revenue</td><td>$daily_net_revenue</td><td>$cur_daily_net_revenue</td><td>$forecast_net_revenue</td></tr>\n";
            echo "<tr><th>Number of Products Sold</th><td>$number_of_products_sold</td><td>$daily_number_of_products_sold</td><td>$cur_daily_number_of_products_sold</td><td>$forecast_number_of_products_sold</td></tr>\n";
            echo "</table>";
            echo "<smalltext>Generate through <strong>Historical Forecasting</strong> by comparing daily average: If daily average for this week is higher than previous, +5% on previous weekly average</smalltext>\n<br/><br/>";
        }
        
        
        
        $revenue_per_product = [];
        foreach ($saledetails as $saledetail) {
          if (!array_key_exists($saledetail["ProductID"], $revenue_per_product)) {
            $revenue_per_product[$saledetail["ProductID"]]["quantity"] = 0;
          };
          $revenue_per_product[$saledetail["ProductID"]]["quantity"] += intval($saledetail["Quantity"]);
        }
        uasort($revenue_per_product, function($a, $b) {
          return $b['quantity'] - $a['quantity'];
        });
        echo "<h3>Suggestion for restock:</h3>\n";
        echo "<table border='1'>\n";
        echo "<tr><th>ProductID</th><th>Average Quantity Sold per $temp</th><th>Current Stock</th><th>Suggestion</th></tr>\n";
        $count = 15;
        $i = 0;
        foreach($revenue_per_product as $productID => $product) {
          $average_quantity_sold = round($product["quantity"]/3);
          $currentstock = $products[$productID-1]['Stock'];
          if($average_quantity_sold > $currentstock){
              $restock = "<strong>Restock required</strong>";
          } else {
              $restock = "Good";
          }
          echo "<tr><td>", $productID, "</td>\n";
          echo "<td>", $average_quantity_sold, "</td>\n";
          echo "<td>", $currentstock,"</td>\n";
          echo "<td>$restock</td>\n";
          $i++;
          if ($i == $count) break;
        };
        echo "</table>\n<br>";}
  ?>

    <button type="button" onclick="tableToCSV()" class="butt3">
        Download
    </button>
    <script type="text/javascript">
        
        function generateMonthlyGraph(){
            Morris.Line({
                element: 'monthlychart',
                data: [<?php echo $chart_data ?>],
                xkey: 'month',
                ykeys: ['revenue','sale'],
                labels: ['revenue','sale'],
                xLabels: "month"
            });
        }

        function generateWeeklyGraph(){
            Morris.Line({
            element: 'weeklychart',
            data: [<?php echo $chart_data ?>],
            xkey: 'w',
            ykeys: ['revenue','sale'],
            labels: ['revenue','sale'],
            parseTime: false
        });
        }

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
 
            temp_link.download = "SalesReportSummary.csv";
            var url = window.URL.createObjectURL(CSVFile);
            temp_link.href = url;
 
            temp_link.style.display = "none";
            document.body.appendChild(temp_link);

            temp_link.click();
            document.body.removeChild(temp_link);
        }
    </script>

  </article>
  <?php
      include "./includes/footer.inc";
  ?>
</body>
</html>
<?php
}}
?>
