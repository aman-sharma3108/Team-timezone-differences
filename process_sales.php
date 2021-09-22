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
        //Return to sales form
        header ("location: salesform.php?status=not_submitted");
        exit;
    }

    $sales_form_error = array();
    $result = true;


    //process date
    if (isset($_POST["dos"])) {
        $dos = sanitise_input($_POST["dos"]);
    }
    else {
        //Return to enquire form
        header ("location: salesform.php?status=not_submited");
        exit;
    }

    //process time
    if (isset($_POST["time"])) {
        $time = sanitise_input($_POST["time"]);
    }
    else {
        //Return to enquire form
        header ("location: salesform.php?status=not_submited");
        exit;
    }

    //process product name
    if (isset($_POST["productname"])) {
        $productname = sanitise_input($_POST["productname"]);
        if ($productname == "") {
            $sales_form_error[] = "product_name_empty";
            $result = false;
        }
        else if (!preg_match("/^[a-zA-Z ]{1,}$/", $productname)) {
            $sales_form_error[] = "product_name_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: salesform.php?status=not_submited");
        exit;
    }
    
    //process quantity
    if (isset($_POST["quantity"])) {
        $quantity = sanitise_input($_POST["quantity"]);
        if ($quantity == "") {
            $sales_form_error[] = "quantity_empty";
            $result = false;
        }
        else if (!preg_match("/^[0-9]+$/", $quantity)) {
            $sales_form_error[] = "quantity_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: salesform.php?status=not_submited");
        exit;
    }

    //process tprice  (price needs to be calculated based on quantity)
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
        //Return to enquire form
        header ("location: salesform.php?status=not_submited");
        exit;
    }

    //process employeeID
    if (isset($_POST["empid"])) {
        $empid = sanitise_input($_POST["empid"]);
        if ($empid == "") {
            $sales_form_error[] = "empid_empty";
            $result = false;
        }
        else if (!preg_match("/^PHP-[0-9]+$/", $empid)) {
            $sales_form_error[] = "empid_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: salesform.php?status=not_submited");
        exit;
    }
    
    $_SESSION["dos"] = $dos;
    $_SESSION["time"] = $time;
    $_SESSION["productname"] = $productname;
    $_SESSION["quantity"] = $quantity;
    $_SESSION["tprice"] = $tprice;
    $_SESSION["empid"] = $empid;
    $_SESSION["sales_form_error"] = $sales_form_error;

    if ($result == false) {
        header("Location: salesform.php?status=invalid_input");
        exit;
    }

    //connect to database and create database and tables if not exist
    require_once ("./includes/db.inc.php");

    //if database connection is set
    if ($connection) {
        $insert_query = "INSERT INTO sales(SaleDate, SaleTime, ProductName, Quantity, TotalPrice, EmployeeID)
        VALUES ('$dos', '$time', '$productname', '$quantity', '$tprice', '$empid');";
        
        $execute_result = mysqli_query($connection, $insert_query);

        if ($execute_result) { //execute query successfully
            header("Location: salesform.php?status=success");
        }
        else {
            header("Location: salesform.php?status=database_error");
        }
        mysqli_close($connection);// Close the database connect
    }
?>