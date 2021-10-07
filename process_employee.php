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
        header ("location: employeeform.php?status=not_submitted");
        exit;
    }

    $employee_form_error = array();
    $result = true;


    if (isset($_POST["emp_uname"])) {
        $emp_uname = sanitise_input($_POST["emp_uname"]);
        if ($emp_uname == "") {
            $employee_form_error[] = "employee_uname_empty";
            $result = false;
        }
        else if (!preg_match("/^[A-Za-z][A-Za-z0-9]{5,31}$/", $emp_uname)) {
            $employee_form_error[] = "employee_uname_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: employeeform.php?status=not_submited");
        exit;
    }
    
    //process product name
    if (isset($_POST["emp_fname"])) {
        $emp_fname = sanitise_input($_POST["emp_fname"]);
        if ($emp_fname == "") {
            $employee_form_error[] = "employee_fname_empty";
            $result = false;
        }
        else if (!preg_match("/^[a-zA-Z ]{1,}$/", $emp_fname)) {
            $employee_form_error[] = "employee_fname_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: employeeform.php?status=not_submited");
        exit;
    }

    if (isset($_POST["emp_lname"])) {
        $emp_lname = sanitise_input($_POST["emp_lname"]);
        if ($emp_lname == "") {
            $employee_form_error[] = "employee_lname_empty";
            $result = false;
        }
        else if (!preg_match("/^[a-zA-Z ]{1,}$/", $emp_lname)) {
            $employee_form_error[] = "employee_lname_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: employeeform.php?status=not_submited");
        exit;
    }
       
    if (isset($_POST["emp_pass"])) {
        $emp_pass = sanitise_input($_POST["emp_pass"]);
        if ($emp_pass == "") {
            $employee_form_error[] = "employee_pass_empty";
            $result = false;
        }
        else if (!preg_match("/^[A-Za-z][A-Za-z0-9]{5,31}$/", $emp_pass)) {
            $employee_form_error[] = "employee_pass_invalid";
            $result = false;
        }
    }
    else {
        //Return to enquire form
        header ("location: employeeform.php?status=not_submited");
        exit;
    }
      

    $_SESSION["emp_uname"] = $emp_uname;
    $_SESSION["emp_fname"] = $emp_fname;
    $_SESSION["emp_lname"] = $emp_lname;
    $_SESSION["emp_pass"] = $emp_pass;
    $_SESSION["employee_form_error"] = $employee_form_error;

    if ($result == false) {
        header("Location: employeeform.php?status=invalid_input");
        exit;
    }

    //connect to database and create database and tables if not exist
    require_once ("./includes/db.inc.php");

    //if database connection is set
    if ($connection) {
        $insert_query = "INSERT INTO users (Username, Firstname, Lastname, Password)
        VALUES ('$emp_uname', '$emp_fname', '$emp_lname', '$emp_pass');";
        
        $execute_result = mysqli_query($connection, $insert_query);

        if ($execute_result) { //execute query successfully
            header("Location: employeeform.php?status=success");
        }
        else {
            header("Location: employeeform.php?status=database_error");
        }
        mysqli_close($connection);// Close the database connect
    }
?>
