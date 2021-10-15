<?php
    session_start();

    function sanitise_input ($data) {
        $data = trim ($data);
        $data = stripslashes ($data);
        $data = htmlspecialchars ($data);
        $data = str_replace(array("\r", "\n"), '', $data); //new line to filter line breaks
        return $data;
    }

    //check if the submit button is clicked
    if (!isset($_POST['submit'])) { //if not
        //Return to product form
        header ("location: viewproduct.php?status=not_submitted");
        exit;
    }

    $editproduct_form_error = array();
    $result = true;


    //process ID
    if (isset($_POST["product_id"])) {
        $product_id = sanitise_input($_POST["product_id"]);
    }
    else {
        //Return to enquire form
        header ("location: viewproduct.php?status=not_submited");
        exit;
    }


    //process product name
    if (isset($_POST["product_name"])) {
        $product_name = sanitise_input($_POST["product_name"]);
        if ($product_name == "") {
            $editproduct_form_error[] = "product_name_empty";
            $result = false;
        }
        else if (!preg_match("/^[a-zA-Z ]{1,}$/", $product_name)) {
            $editproduct_form_error[] = "product_name_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: viewproduct.php?status=not_submited");
        exit;
    }
    
    //process category
    if (isset($_POST["category"])) {
        $category = sanitise_input($_POST["category"]);
    }
    else {
        //Return to enquire form
        header ("location: viewproduct.php?status=not_submited");
        exit;
    }

    //process price
    if (isset($_POST["price"])) {
        $price = sanitise_input($_POST["price"]);
        if ($price == "") {
            $editproduct_form_error[] = "price_empty";
            $result = false;
        }
        else if (!preg_match("/^([0-9]+[.])?[0-9]+$/", $price)) {
            $editproduct_form_error[] = "price_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: viewproduct.php?status=not_submited");
        exit;
    }

    //process comments
    if (isset($_POST["comments"])) {
        $comments = sanitise_input($_POST["comments"]);
    }
    else {
        //Return to enquire form
        header ("location: viewproduct.php?status=not_submited");
        exit;
    }
  
    //process stock
    if (isset($_POST["stock"])) {
        $stock = sanitise_input($_POST["stock"]);
        if ($stock == "") {
            $editproduct_form_error[] = "stock_empty";
            $result = false;
        }
        else if (!preg_match("/^[0-9]+$/", $stock)) {
            $editproduct_form_error[] = "stock_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: viewproduct.php?status=not_submited");
        exit;
    }

    $_SESSION["product_id"] = $product_id;
    $_SESSION["product_name"] = $product_name;
    $_SESSION["category"] = $category;
    $_SESSION["price"] = $price;
    $_SESSION["comments"] = $comments;
    $_SESSION["stock"] = $stock;
    $_SESSION["editproduct_form_error"] = $editproduct_form_error;

    if ($result == false) {
        header("Location: viewproduct.php?status=invalid_input");
        exit;
    }

    //connect to database and create database and tables if not exist
    require_once ("./includes/db.inc.php");

    //if database connection is set
    
        $update_query = "UPDATE products SET ProductName='$product_name',Category='$category',Price=$price,Comments='$comments',Stock=$stock WHERE ProductID = '$product_id'";
        $result=mysqli_query($connection, $update_query);

        if ($result) { //execute query successfully
            header("Location: viewproduct.php?status=success");
        }
        else {
            header("Location: viewproduct.php?status=database_error");
        }
        mysqli_close($connection);// Close the database connect
?>
