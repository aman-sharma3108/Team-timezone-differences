<?php
  session_start();
  if (!isset($_SESSION["Login"])) {
    header ("location: logout.php");
    exit;
  }
  else {
    if ($_SESSION["Login"]!=true) {
      header ("location: logout.php");
      exit;
    }
    else {
?>
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
    <article class="art">
        <div class="art2">
    <h1 class="head">Displaying All Products</h1>
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
</div>
    <!-- start of download section-->
    <button type="button" onclick="tableToCSV()" class="butt">
        Download
    </button>
</article>
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
        <!-- end of download section-->

    <!-- new section-->
    <?php
        function select_category($category) {
            if (isset($_SESSION["category"])) {
                if ($_SESSION["category"] == $category) {
                    echo " selected=\"selected\"";
                }
            }
        }

        if(isset($_GET["status"]))
        {
            $status = $_GET["status"];
        }
        else $status = null;

        if ($status == "invalid_input") 
        {
            if (isset($_SESSION["editproduct_form_error"])) $editproduct_form_error = $_SESSION["editproduct_form_error"];
            else $editproduct_form_error = null;
        }
        else $editproduct_form_error = null;
    ?>

	<h1>Edit a Product in the Catalogue</h1>

    <?php
        if ($status == "not_submitted") echo "<p class='error'>Please fill in the form and submit!</p>";
        if ($status == "invalid_input") echo "<p class='error'>Invalid Input!</p>";
        if ($status == "success") echo "<p class='success'>Edited product successfully!</p>";
        if ($status == "database_error") echo "<p class='error'>Failed to edit product in database!</p>";
    ?>

    <!--dont reload to original edit page-->
    <!--https://www.geeksforgeeks.org/how-to-fill-all-input-fields-automatically-from-database-by-entering-input-in-one-textbox-using-php/-->
	<form method="post" action="autoeditprocess_product.php">
	<fieldset>
        <legend>Edit Product Details</legend>
            <?php
                if ($editproduct_form_error != null) {
                    if (in_array("product_id_empty", $editproduct_form_error)) echo "<p class='error'>Please fill in Product ID</p>";
                    elseif (in_array("product_id_invalid", $editproduct_form_error)) echo "<p class='error'>Please fill in valid Product ID</p>";
                }
            ?>
            <p class="row">	
                <label for="product_id">Product ID: </label>
                <input type="text" name="product_id" id="product_id" onkeyup="GetDetail(this.value)" value="" value="<?php if ($status == "invalid_input") echo $_SESSION["product_id"] ?>"/>
            </p>
            
            <?php
                if ($editproduct_form_error != null) {
                    if (in_array("product_name_empty", $editproduct_form_error)) echo "<p class='error'>Please fill in Product Name</p>";
                    elseif (in_array("product_name_invalid", $editproduct_form_error)) echo "<p class='error'>Please fill in valid Product Name</p>";
                }
            ?>
            <p class="row">	
                <label for="product_name">Product Name: </label>
                <input type="text" name="product_name" id="product_name" value="<?php if ($status == "invalid_input") echo $_SESSION["product_name"] ?>"/>
            </p>
            <p>
                <label for="category">Category</label> 
                <select name="category" id="category">
                    <option value="GSL" <?php select_category("GSL") ?>>General Sales List</option>			
                    <option value="controlled" <?php select_category("controlled") ?>>Controlled Drugs</option>
                    <option value="prescription" <?php select_category("prescription") ?>>Prescription Only</option>
                    <option value="other" <?php select_category("other") ?>>Other</option>
                </select>
            </p>
            <?php
                if ($editproduct_form_error != null) {
                    if (in_array("price_empty", $editproduct_form_error)) echo "<p class='error'>Please fill in Price</p>";
                    elseif (in_array("price_invalid", $editproduct_form_error)) echo "<p class='error'>Please fill in valid Price</p>";
                }
            ?>
            <p class="row">	
                <label for="price">Product Price: </label>
                <input type="text" name="price" id="price" value="<?php if ($status == "invalid_input") echo $_SESSION["price"] ?>"/>
            </p>
            <p>
                <label for="comments">Comments</label>
                <textarea id="comments" name="comments" rows="4" cols="40"><?php if ($status == "invalid_input") echo $_SESSION["comments"] ?></textarea>
            </p>
            <?php
                if ($editproduct_form_error != null) {
                    if (in_array("stock_empty", $editproduct_form_error)) echo "<p class='error'>Please fill in Stock</p>";
                    elseif (in_array("stock_invalid", $editproduct_form_error)) echo "<p class='error'>Please fill in valid Stock</p>";
                }
            ?>
            <p>
                <label for="stock">No. added to stock</label>
                <input type="text" id="stock" name="stock" maxlength="4" size="2" value="<?php if ($status == "invalid_input") echo $_SESSION["stock"] ?>"/> <!--the html requiremnts should match the database settings later-->
            </p>
            <p>	
                <input type="submit" id="submit" name="submit" value="Confirm" />
            </p>
	</fieldset>
	</form>

    <script>
        function GetDetail(str) {
            if (str.length == 0) {
                document.getElementById("product_name").value = "";
                document.getElementById("category").value = "";
                document.getElementById("price").value = "";
                document.getElementById("comments").value = "";
                document.getElementById("stock").value = "";
                return;
            }
            else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && 
                            this.status == 200) {
                        var myObj = JSON.parse(this.responseText);
                        document.getElementById
                            ("product_name").value = myObj[0];                  
                        document.getElementById
                            ("category").value = myObj[1];
                        document.getElementById
                            ("price").value = myObj[2]; 
                        document.getElementById
                            ("comments").value = myObj[3]; 
                        document.getElementById
                            ("stock").value = myObj[4]; 
                    }
                };
                xmlhttp.open("GET", "autofill.php?product_id=" + str, true);
                xmlhttp.send();
            }
        }
    </script>
    
    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>
<?php
}}
?>
