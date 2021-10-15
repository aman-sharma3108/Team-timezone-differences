<!DOCTYPE html>
<html lang="en">
<head>
<title>Sales</title>

<meta charset="utf-8" />
<meta name="description" content="Product form"  />
<meta name="keywords" content="Form, Input" />
<link href= "styles/style.css" rel="stylesheet"/>
<script src="js/scripts.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
<!-- Description: Form Input for sale -->
<!-- Author: Manging software projects team -->
<!-- Date: 20/09/21 -->

</head>
<body>
    <?php
        session_start();

        if(isset($_GET["status"]))
        {
            $status = $_GET["status"];
        }
        else $status = null;

        if ($status == "invalid_input") 
        {
            if (isset($_SESSION["sales_form_error"])) $sales_form_error = $_SESSION["sales_form_error"];
            else $sales_form_error = null;
        }
        else $sales_form_error = null;
        
        include "./includes/header.inc";
        include "./includes/nav.inc";
    ?>

	<h1>Making a new Sale</h1>

    <?php
        if ($status == "not_submitted") echo "<p class='error'>Please fill in the form and submit!</p>";
        if ($status == "invalid_input") echo "<p class='error'>Invalid Input!</p>";
        if ($status == "success") echo "<p class='success'>Complete Sale successfully!</p>";
        if ($status == "database_error") echo "<p class='error'>Failed to add sale to database!</p>";
        if ($status == "no_product_in_database") echo "<p class='error'>Please add products before adding sales</p>";
    ?>

	<form method="POST" action="process_sales.php" id="new_sale">
	<fieldset>
        <legend>Current Sale</legend>
            <p>
                <label for="dos">Sale Date and Time</label> 
                <input type="datetime-local" name= "dos" id="dos" value="<?php if ($status == "invalid_input") echo $_SESSION["dos"] ?>"/>
            </p>

            <?php
                if ($sales_form_error != null) {
                    if (in_array("product_name_empty", $sales_form_error)) echo "<p class='error'>Please fill in Product Name</p>";
                    elseif (in_array("product_name_invalid", $sales_form_error)) echo "<p class='error'>Please fill in valid Product Name</p>";
                }
            ?>
            <div id="input_wrapper">
                <p class='row'>	
                    <label for='product_dropdown'>Product: </label>
                    <select id="product_dropdown" onchange="getPrice()">
                        <?php
                            require_once("./includes/db.inc.php");
                            if ($connection) {
                                $select_query = "SELECT ProductID, ProductName FROM products";
                                $result = mysqli_query($connection, $select_query);
                                if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $id = $row["ProductID"];
                                        $name = $row["ProductName"];
                                        echo "<option value='$id'>$name</option>";
                                    }
                                } else {
                                    echo "<option value=''>Product Not Available!</option>";
                                }
                            }
                        ?>
                    </select>
                    <label for='price_dropdown'>Price: </label>
                    <select id="price_dropdown" disabled>
                        <?php
                            require_once("./includes/db.inc.php");
                            if ($connection) {
                                $select_query = "SELECT ProductID, Price FROM products";
                                $result = mysqli_query($connection, $select_query);
                                if (mysqli_num_rows($result) > 0) {
                                    // output data of each row
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $id = $row["ProductID"];
                                        $price = $row["Price"];
                                        echo "<option value='$id'>$price</option>";
                                    }
                                } else {
                                    echo "<option value=''>Product Not Available!</option>";
                                }
                            }
                        ?>
                    </select>
                    <label for='quantity'>Quantity: </label>
                    <input type='text' placeholder='Quantity' id='quantity' onchange="calculateSubTotal()" maxlength='4' size='4'/>
                    <input type='text' placeholder='Subtotal' id='subtotal' size='6' readonly/> 
                    
                    <div><button type="button" name="add" id="AddProduct">Add Product</button></div>
                </p>
                
            <?php
                $count = 0;

                //get count of products
                if ($status == "invalid_input") {
                    if (isset($_SESSION["product_count"])) $count = $_SESSION["product_count"];
                }
                for ($i=0; $i<$count; $i++) {
                    //print errors
                    if (in_array("products_empty".$i, $sales_form_error)) echo "<p class='error'>Please fill in Product ID</p>";
                    elseif (in_array("products_invalid".$i, $sales_form_error)) echo "<p class='error'>Please fill in valid Product ID</p>";
                    elseif (in_array("products_not_available".$i, $sales_form_error)) echo "<p class='error'>Product ID is not available in the database</p>";
                    if (in_array("quantities_empty".$i, $sales_form_error)) echo "<p class='error'>Please fill in Quantity</p>";
                    elseif (in_array("quantities_invalid".$i, $sales_form_error)) echo "<p class='error'>Please fill in valid Quantity</p>";
                    if (in_array("subtotals_empty".$i, $sales_form_error)) echo "<p class='error'>Please fill in Subtotal</p>";
                    elseif (in_array("subtotals_invalid".$i, $sales_form_error)) echo "<p class='error'>Please fill in valid Subtotal</p>";
                    
                    //get values previously inputted
                    $product = $_SESSION["products"][$i]; 
                    $quantity = $_SESSION["quantities"][$i];
                    $subtotal = $_SESSION["subtotals"][$i];

                    //print html products
                    echo "<p class='row'>	
                    <label for='productname'>Product: </label>
                    <input type='text' placeholder='Product ID' class='products' name='products[]' value='$product' readonly>
                    x
                    <input type='text' placeholder='Quantity' class='quantities' name='quantities[]' maxlength='4' size='4' value='$quantity' readonly/>
                    =
                    <input type='text' placeholder='Subtotal' class='subtotals' name='subtotals[]' onchange='calc()' size='6' value='$subtotal' readonly/> 
                    </p>
                    <button type='button' class='removeclass'>x</button></p>";
                }
            ?>
            </div>


            <?php
                if ($sales_form_error != null) {
                    if (in_array("quantity_empty", $sales_form_error)) echo "<p class='error'>Please fill in quantity needed</p>";
                    elseif (in_array("quantity_invalid", $sales_form_error)) echo "<p class='error'>Please fill in valid quantity value</p>";
                }
            ?>
            

            <?php
                if ($sales_form_error != null) {
                    if (in_array("tprice_empty", $sales_form_error)) echo "<p class='error'>Please fill in the Products</p>";
                    elseif (in_array("tprice_invalid", $sales_form_error)) echo "<p class='error'>The total is not valid</p>";
                }
            ?>
                               
            <p class="row">	<label for="tprice">Total Price: </label>
                <input type="text" name="tprice" id="tprice" value="<?php if ($status == "invalid_input") echo $_SESSION["tprice"] ?>" readonly/>
            </p>
            


            <?php
                if ($sales_form_error != null) {
                    if (in_array("empid_empty", $sales_form_error)) echo "<p class='error'>Please fill in Employee ID</p>";
                    elseif (in_array("empid_invalid", $sales_form_error)) echo "<p class='error'>Please fill in valid Employee ID</p>";
                }
            ?>
            <p class="row">	
                <label for="empid">Employee ID: </label>
                <input type="text" name="empid" id="empid" value="<?php if ($status == "invalid_input") echo $_SESSION["empid"] ?>"/>
            </p>

            <p>
                <input type="submit" id="submit" name="submit" value="Complete Sale" />
            </p>
            </fieldset>
	</form>

    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>


