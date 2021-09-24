<?php
    session_start();

    function sanitise_input ($data) {
        $data = trim ($data);
        $data = stripslashes ($data);
        $data = htmlspecialchars ($data);
        return $data;
    }

    //check if the submit button is clicked
    if (!isset($_POST['submit'])) { //if not
        //Return to product form
        header ("location: editsalesform.php?status=not_submitted");
        exit;
    }

    $editsale_form_error = array();
    $result = true;


    //process ID
    if (isset($_POST["sale_id"])) {
        $product_id = sanitise_input($_POST["sale_id"]);
    }
    else {
        //Return to enquire form
        header ("location: editsalesform.php?status=not_submited");
        exit;
    }

    if (isset($_POST["dos"])) {
        $comments = sanitise_input($_POST["dos"]);
    }
    else {
        //Return to enquire form
        header ("location: editsalesform.php?status=not_submited");
        exit;
    }

    //process total price
    if (isset($_POST["tprice"])) {
        $tprice = sanitise_input($_POST["tprice"]);
        if ($tprice == "") {
            $sales_form_error[] = "tprice_empty";
            $result = false;
        }
        else if (!preg_match("/^([0-9]+[.])?[0-9]+$/", $tprice)) {
            $sales_form_error[] = "tprice_invalid";
            $result = false;
        }
    }
    else {
        //Return to sales form
        header ("location: editsalesform.php?status=not_submitted");
        exit;
    }

    if (isset($_POST["empid"])) {
        $empid = sanitise_input($_POST["empid"]);
        if ($empid == "") {
            $editsale_form_error[] = "empid_empty";
            $result = false;
        }
        else if (!preg_match("/^[0-9]+$/", $empid)) {
            $editsale_form_error[] = "empid_invalid";
            $result = false;
        }
    }
    else {
        //Return to sales form
        header ("location: editsalesform.php?status=not_submitted");
        exit;
    }

    require_once ("./includes/db.inc.php");

    if ($connection) {
        $select_query = "SELECT ProductID FROM products";
        $result = mysqli_query($connection, $select_query);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $database_products[] = $row["ProductID"];
            }
        } else {
            //Return to sales form
            header ("location: editsalesform.php?status=no_products_in_database");
            exit;
        }
            
    }

    //process product ids
    if (isset($_POST["products"])) {
        $count = -1;
        foreach($_POST["products"] as $product) {
            $count += 1;
            $product = sanitise_input($product);
            if ($product == "") {
                $editsale_form_error[] = "products_empty".$count;
                $result = false;
            }
            else if (!preg_match("/^[0-9]+$/", $product)) {
                $editsale_form_error[] = "products_invalid".$count;
                $result = false;
            }
            else if (!in_array($product, $database_products)) {
                $editsale_form_error[] = "products_not_available".$count;
                $result = false;
            }
            $products[] = $product;
        }
    }
    else {
        //Return to sales form
        header ("location: editsalesform.php?status=not_submitted");
        exit;
    }

    //process quantities
    if (isset($_POST["quantities"])) {
        $count = -1;
        foreach($_POST["quantities"] as $quantity) {
            $count += 1;
            $quantity = sanitise_input($quantity);
            if ($quantity == "") {
                $editsale_form_error[] = "quantities_empty".$count;
                $result = false;
            }
            else if (!preg_match("/^[0-9]+$/", $quantity)) {
                $editsale_form_error[] = "quantities_invalid".$count;
                $result = false;
            }
            $quantities[] = $quantity;
        }
    }
    else {
        //Return to sales form
        header ("location: editsalesform.php?status=not_submitted");
        exit;
    }

    //process subtotals
    if (isset($_POST["subtotals"])) {
        $count = -1;
        foreach($_POST["subtotals"] as $subtotal) {
            $count += 1;
            $subtotal = sanitise_input($subtotal);
            if ($subtotal == "") {
                $editsale_form_error[] = "subtotals_empty".$count;
                $result = false;
            }
            else if (!preg_match("/^([0-9]+[.])?[0-9]+$/", $subtotal)) {
                $editsale_form_error[] = "subtotals_invalid".$count;
                $result = false;
            }
            $subtotals[] = $subtotal;
        }
    }
    else {
        //Return to sales form
        header ("location: editsalesform.php?status=not_submitted");
        exit;
    }
    
    //count of product
    $product_count = count($products);

    $_SESSION["sale_id"] = $sale_id;
    $_SESSION["dos"] = $dos;
    $_SESSION["tprice"] = $tprice;
    $_SESSION["empid"] = $empid;
    $_SESSION["product_count"] = $product_count;
    $_SESSION["products"] = $products;
    $_SESSION["quantities"] = $quantities;
    $_SESSION["subtotals"] = $subtotals;
    $_SESSION["editsale_form_error"] = $editsale_form_error;

    if ($result == false) {
        header("Location: editsalesform.php?status=invalid_input");
        exit;
    }


    //if database connection is set
    
    if ($connection) {
        $update_query = "UPDATE sales SET SaleDateTime='$dos',PriceTotal=$tprice,EmployeeID='$empid' WHERE SaleID = '$sale_id';";
        

        for($i=0; $i<$product_count; $i++) {
            $product = $products[$i];
            $quantity = $quantities[$i];
            $subtotal = $subtotals[$i];
            $update_query = "UPDATE productsalelinks SET ProductID='$product',Quantity=$quantity, SubTotal=$subtotal WHERE SaleID = '$sale_id';";
            
        }
        $execute_result = mysqli_multi_query($connection, $update_query);

        if ($execute_result) { //execute query successfully
            header("Location: editsalesform.php?status=success");
        }
        else {
            header("Location: editsalesform.php?status=database_error");
        }
        mysqli_close($connection);// Close the database connect
    }
?>