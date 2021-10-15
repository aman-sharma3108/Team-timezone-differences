<!DOCTYPE html>
<html lang="en">
<head>
<title>Products</title>

<meta charset="utf-8" />
<meta name="description" content="Product form"  />
<meta name="keywords" content="Form, Input" />
<link href= "styles/style.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
<script src="js/scripts.js"></script>
<!-- Description: Form Input for product -->
<!-- Author: Calvin Bell -->
<!-- Date: 07/09/21 -->

</head>
<body>
    <?php
        session_start();

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
            if (isset($_SESSION["product_form_error"])) $product_form_error = $_SESSION["product_form_error"];
            else $product_form_error = null;
        }
        else $product_form_error = null;

        include "./includes/header.inc";
        include "./includes/nav.inc";
    ?>

	<h1>Add A New Product To The Catalogue</h1>

    <?php
        if ($status == "not_submitted") echo "<p class='error'>Please fill in the form and submit!</p>";
        if ($status == "invalid_input") echo "<p class='error'>Invalid Input!</p>";
        if ($status == "success") echo "<p class='success'>Add product successfully!</p>";
        if ($status == "database_error") echo "<p class='error'>Fail to add product to database!</p>";
    ?>

	<form method="post" action="process_product.php">
	<fieldset>
        <legend>New Product Details</legend>
            <?php
                if ($product_form_error != null) {
                    if (in_array("product_name_empty", $product_form_error)) echo "<p class='error'>Please fill in Product Name</p>";
                    elseif (in_array("product_name_invalid", $product_form_error)) echo "<p class='error'>Please fill in valid Product Name</p>";
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
                if ($product_form_error != null) {
                    if (in_array("price_empty", $product_form_error)) echo "<p class='error'>Please fill in Price</p>";
                    elseif (in_array("price_invalid", $product_form_error)) echo "<p class='error'>Please fill in valid Price</p>";
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
                if ($product_form_error != null) {
                    if (in_array("stock_empty", $product_form_error)) echo "<p class='error'>Please fill in Stock</p>";
                    elseif (in_array("stock_invalid", $product_form_error)) echo "<p class='error'>Please fill in valid Stock</p>";
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

    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>
