<!DOCTYPE html>
<html lang="en">
<head>
<title>Sales</title>

<meta charset="utf-8" />
<meta name="description" content="Product form"  />
<meta name="keywords" content="Form, Input" />
<link href= "styles/style.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="js/scripts.js"></script>
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
    ?>

	<form method="post" action="http://mercury.swin.edu.au/it000000/formtest.php" id="new_sale">
	<fieldset>
        <legend>Current Sale</legend>
            <p>
                <label for="dos">Date of Sale</label> 
                <input type="date" name= "dos" id="dos" placeholder="dd-mm-yyyy" maxlength="10" size="10" value="<?php if ($status == "invalid_input") echo $_SESSION["dos"] ?>"/>
            </p>


            <p>
                <label for="time">Time of Sale:</label>
                <input type="time" id="time" name="time" value="<?php if ($status == "invalid_input") echo $_SESSION["time"] ?>">
            </p>
            <script>
             var dt = new Date();
             document.getElementById("time").innerHTML = dt.toLocaleTimeString();
            </script>


            <?php
                if ($sales_form_error != null) {
                    if (in_array("product_name_empty", $sales_form_error)) echo "<p class='error'>Please fill in Product Name</p>";
                    elseif (in_array("product_name_invalid", $sales_form_error)) echo "<p class='error'>Please fill in valid Product Name</p>";
                }
            ?>
            <div id="input_wrapper">
            <p class="row">	
                <label for="productname">Product: </label>
                <!-- <input type="text" placeholder="Product ID" id="product0" name="product0" value="<?php if ($status == "invalid_input") echo $_SESSION["productname"] ?>"> -->
                <input type="text" placeholder="Product ID" class="products" name="products[]" value="<?php if ($status == "invalid_input") echo $_SESSION["productname"] ?>">
                x
                <!-- <input type="text" placeholder="Quantity" id="quantity0" name="quantity0" maxlength="4" size="4" value="<?php if ($status == "invalid_input") echo $_SESSION["quantity"] ?>"/>  -->
                <input type="text" placeholder="Quantity" class="quantities" name="quantities[]" maxlength="4" size="4" value="<?php if ($status == "invalid_input") echo $_SESSION["quantity"] ?>"/>
                =
                <input type="text" placeholder="Subtotal" class="subtotal" name="subtotal[]" onchange="calc()" size="6"/> 
            </p>
            </div>
            <div><button type="button" name="add" id="AddMoreFileBox">Add More</button></div>


            <?php
                if ($sales_form_error != null) {
                    if (in_array("quantity_empty", $sales_form_error)) echo "<p class='error'>Please fill in quantity needed</p>";
                    elseif (in_array("quantity_invalid", $sales_form_error)) echo "<p class='error'>Please fill in valid quantity value</p>";
                }
            ?>
            

            ||||<?php
                if ($sales_form_error != null) {
                    if (in_array("tprice_empty", $sales_form_error)) echo "<p class='error'>-------</p>";
                    elseif (in_array("tprice_invalid", $sales_form_error)) echo "<p class='error'>---------</p>";
                }
            ?>
                                                                       <!-- price needs to be automatically input based on quantity needed -->                      
            <p class="row">	<label for="tprice">Total Price: </label>
                <input type="text" name="tprice" id="tprice" value="<?php if ($status == "invalid_input") echo $_SESSION["tprice"] ?>"/>
            </p>
            ||||


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
                <input type="submit" id="submit" value="Complete Sale" />
            </p>
            </fieldset>
	</form>

    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>