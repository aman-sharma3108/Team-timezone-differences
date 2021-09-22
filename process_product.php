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
        header ("location: productform.php?status=not_submitted");
        exit;
    }

    $product_form_error = array();
    $result = true;

    //process product name
    if (isset($_POST["product_name"])) {
        $product_name = sanitise_input($_POST["product_name"]);
        if ($product_name == "") {
            $product_form_error[] = "product_name_empty";
            $result = false;
        }
        else if (!preg_match("/^[a-zA-Z ]{1,}$/", $product_name)) {
            $product_form_error[] = "product_name_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: productform.php?status=not_submited");
        exit;
    }
    
    //process category
    if (isset($_POST["category"])) {
        $category = sanitise_input($_POST["category"]);
    }
    else {
        //Return to enquire form
        header ("location: productform.php?status=not_submited");
        exit;
    }

    //process price
    if (isset($_POST["price"])) {
        $price = sanitise_input($_POST["price"]);
        if ($price == "") {
            $product_form_error[] = "price_empty";
            $result = false;
        }
        else if (!preg_match("/^([0-9]+[.])?[0-9]+$/", $price)) {
            $product_form_error[] = "price_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: productform.php?status=not_submited");
        exit;
    }

    //process comments
    if (isset($_POST["comments"])) {
        $comments = sanitise_input($_POST["comments"]);
    }
    else {
        //Return to enquire form
        header ("location: productform.php?status=not_submited");
        exit;
    }
  
    //process stock
    if (isset($_POST["stock"])) {
        $stock = sanitise_input($_POST["stock"]);
        if ($stock == "") {
            $product_form_error[] = "stock_empty";
            $result = false;
        }
        else if (!preg_match("/^[0-9]+$/", $stock)) {
            $product_form_error[] = "stock_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: productform.php?status=not_submited");
        exit;
    }

    $_SESSION["product_name"] = $product_name;
    $_SESSION["category"] = $category;
    $_SESSION["price"] = $price;
    $_SESSION["comments"] = $comments;
    $_SESSION["stock"] = $stock;
    $_SESSION["product_form_error"] = $product_form_error;

    if ($result == false) {
        header("Location: productform.php?status=invalid_input");
        exit;
    }

    //connect to database and create database and tables if not exist
    require_once ("./includes/db.inc.php");

    //if database connection is set
    if ($connection) {
        $insert_query = "INSERT INTO products(ProductName, Category, Price, Comments, Stock)
        VALUES ('$product_name', '$category', '$price', '$comments', '$stock');";
        
        $execute_result = mysqli_query($connection, $insert_query);

        if ($execute_result) { //execute query successfully
            header("Location: productform.php?status=success");
        }
        else {
            header("Location: productform.php?status=database_error");
        }
        mysqli_close($connection);// Close the database connect
    }
?>