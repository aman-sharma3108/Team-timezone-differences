<?php
    session_start();

    function sanitise_input ($data) {
        $data = trim ($data);
        $data = stripslashes ($data);
        $data = htmlspecialchars ($data);
        $data = str_replace(array("\r", "\n"), '', $data);
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
        $sale_id = sanitise_input($_POST["sale_id"]);
    }
    else {
        //Return to enquire form
        header ("location: editsalesform.php?status=not_submited");
        exit;
    }

    if (isset($_POST["dos"])) {
        $dos = sanitise_input($_POST["dos"]);
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

    $_SESSION["sale_id"] = $sale_id;
    $_SESSION["dos"] = $dos;
    $_SESSION["tprice"] = $tprice;
    $_SESSION["empid"] = $empid;
    $_SESSION["editsale_form_error"] = $editsale_form_error;

    if ($result == false) {
        header("Location: editsalesform.php?status=invalid_input");
        exit;
    }

    require_once ("./includes/db.inc.php");
    //if database connection is set
    
    $update_query = "UPDATE sales SET SaleDateTime='$dos',PriceTotal='$tprice',EmployeeID='$empid' WHERE SaleID = '$sale_id'";
        $result=mysqli_query($connection, $update_query);

        if ($result) { //execute query successfully
            header("Location: editsalesform.php?status=success");
        }
        else {
            header("Location: editsalesform.php?status=database_error");
        }
        mysqli_close($connection);// Close the database connect
?>