<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="description" content="Digital Sales System" />
  <meta name="keywords" content="Sales, System" />
  <meta name="author" content="Team Timezone Differences"  />
  <title>Sales Summary</title>
  <link href= "styles/style.css" rel="stylesheet"/>
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
            if (isset($_POST["month"])) {
              $year_month = $_POST["month"];
              if ($year_month == null) {
                echo "<p class='error'>Empty Month!</p>";
                break;
              }
              $year_month_array = explode("-", $year_month);
              $year = $year_month_array[0];
              $month = $year_month_array[1];
              $query="SELECT SaleID, SaleDateTime, PriceTotal, EmployeeID 
              FROM sales
              WHERE YEAR(SaleDateTime)='$year' AND MONTH(SaleDateTime)='$month';";
            }
            else break;
          }
          else if ($time_period == "weekly") {
            if (isset($_POST["week"])) {
              $year_week = $_POST["week"];
              if ($year_week == null) {
                echo "<p class='error'>Empty Week!</p>";
                break;
              }
              $year_week_array = explode("-", $year_week);
              $year = $year_week_array[0];
              $week = substr($year_week_array[1],-2);
              $query="SELECT SaleID, SaleDateTime, PriceTotal, EmployeeID 
              FROM sales
              WHERE YEAR(SaleDateTime)='$year' AND WEEK(SaleDateTime,3)='$week';";
              /*
              A week, according to html input type 'week', starts with Monday.
              A starting week of a year must have 4 or more days.
              Therefore, week mode for SQL is 3
              */
            }
            else break;
          }
          else $query="SELECT SaleID, SaleDateTime, PriceTotal, EmployeeID from sales;";
          
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
      } while (0);
        mysqli_close($connection);
  ?>
  <h1>Sales Report Summary</h1>
  <form method="post" action="" id="new_sale">
    <fieldset>
      <legend>Summary Control</legend>
      <p class="row">
        <label for="time_period">Time Period</label>
        <select id="time_period" name="time_period">
          <option value="all">Overall</option>
          <option value="monthly">Monthly</option>
          <option value="weekly">Weekly</option>
        </select>
      </p>
      <p class='row' id="monthly" hidden="hidden">
        <label for="month">Month</label>
        <input type="month" id="month" name="month">
      </p>
      <p class='row' id="weekly" hidden="hidden">
        <label for="week">Week</label>
        <input type="week" id="week" name="week">
      </p>
      <p>	
        <input type="submit" id="submit" name="submit" value="Confirm" />
      </p>
    </fieldset>
  </form>
  <?php
      if (isset($sales)) {
        $number_of_sales = count($sales);
        echo "Number of Sales: $number_of_sales\n<br>";

        $net_revenue = 0;
        foreach($sales as $sale) {
          $net_revenue += floatval($sale["PriceTotal"]);
        }
        echo "Net Revenue: $net_revenue\n<br>";

        $number_of_products_sold = 0;
        foreach($saledetails as $saledetail) {
          $number_of_products_sold += intval($saledetail["Quantity"]);
        }
        echo "Number of Products Sold: $number_of_products_sold\n<br>";

        $revenue_per_product = [];
        foreach ($saledetails as $saledetail) {
          if (!array_key_exists($saledetail["ProductID"], $revenue_per_product)) {
            $revenue_per_product[$saledetail["ProductID"]] = 0;
          };
          $revenue_per_product[$saledetail["ProductID"]] += floatval($saledetail["SubTotal"]);
        }
        arsort($revenue_per_product);
        echo "Top 10 Revenue per Product:\n";
        echo "<table border='1'>\n";
        echo "<tr><th>ProductID</th><th>Revenue</th></tr>\n";
        $count = 10;
        $i = 0;
        foreach($revenue_per_product as $productID => $revenue) {
          echo "<tr><td>", $productID, "</td>\n";
          echo "<td>", $revenue, "</td>\n";
          $i++;
          if ($i == $count) break;
        };
        echo "</table>\n<br>";

        $revenue_per_day = array(
          'Monday' => 0,
          'Tuesday' => 0,
          'Wednesday' => 0,
          'Thursday' => 0,
          'Friday' => 0,
          'Saturday' => 0,
          'Sunday' => 0,
        );
        foreach ($sales as $sale) {
          $revenue_per_day[date("l", strtotime($sale["SaleDateTime"]))] += $sale["PriceTotal"];
        }
        echo "Revenue per Day:";
        echo "<table border='1'>\n";
        echo "<tr><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>\n";
        echo "<tr>";
        foreach ($revenue_per_day as $revenue) {
          echo "<td>$revenue</td>";
        };
        echo "</tr>";
        echo "</table>\n<br>";
      }
      else echo "<p>Sales table does not exist!</p>";
  
  ?>
  <?php
      include "./includes/footer.inc";
  ?>
</body>
</html>