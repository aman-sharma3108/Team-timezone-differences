<?php
  session_start();
  if (!isset($_SESSION["Login"]) || !isset($_SESSION["Role"])) {
    header ("location: logout.php");
    exit;
  }
  else {
    if ($_SESSION["Login"]!=true || $_SESSION["Role"]!="manager") {
      header ("location: logout.php");
      exit;
    }
    else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Employees</title>

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
        if(isset($_GET["status"]))
        {
            $status = $_GET["status"];
        }
        else $status = null;

        if ($status == "invalid_input") 
        {
            if (isset($_SESSION["employee_form_error"])) $employee_form_error = $_SESSION["employee_form_error"];
            else $employee_form_error = null;
        }
        else $employee_form_error = null;

        include "./includes/header.inc";
        include "./includes/nav.inc";
    ?>

	<h1>Add A New Employee to Database</h1>

    <?php
        if ($status == "not_submitted") echo "<p class='error'>Please fill in the form and submit!</p>";
        if ($status == "invalid_input") echo "<p class='error'>Invalid Input!</p>";
        if ($status == "success") echo "<p class='success'>Added employee successfully!</p>";
        if ($status == "database_error") echo "<p class='error'>Failed to add employee to database!</p>";
    ?>

	<form method="post" action="process_employee.php">
	<fieldset>
        <legend>New Employee Details</legend>
        <?php
                if ($employee_form_error != null) {
                    if (in_array("employee_uname_empty", $employee_form_error)) echo "<p class='error'>Please fill in Employee Username</p>";
                    elseif (in_array("employee_uname_invalid", $employee_form_error)) echo "<p class='error'>Username must be at least 5 characters long and include a number</p>";
                }
            ?>
            <p class="row">	
                <label for="emp_uname">Employee Username: </label>
                <input type="text" name="emp_uname" id="emp_uname" value="<?php if ($status == "invalid_input") echo $_SESSION["emp_uname"] ?>"/>
            </p>
            
            <?php
                if ($employee_form_error != null) {
                    if (in_array("employee_fname_empty", $employee_form_error)) echo "<p class='error'>Please fill in Employee Name</p>";
                    elseif (in_array("employee_fname_invalid", $employee_form_error)) echo "<p class='error'>Please fill in valid Employee Name</p>";
                }
            ?>
            <p class="row">	
                <label for="emp_fname">Employee First Name: </label>
                <input type="text" name="emp_fname" id="emp_fname" value="<?php if ($status == "invalid_input") echo $_SESSION["emp_fname"] ?>"/>
            </p>

            <?php
                if ($employee_form_error != null) {
                    if (in_array("employee_lname_empty", $employee_form_error)) echo "<p class='error'>Please fill in Employee Name</p>";
                    elseif (in_array("employee_lname_invalid", $employee_form_error)) echo "<p class='error'>Please fill in valid Employee Name</p>";
                }
            ?>
            <p class="row">	
                <label for="emp_lname">Employee Last Name: </label>
                <input type="text" name="emp_lname" id="emp_lname" value="<?php if ($status == "invalid_input") echo $_SESSION["emp_lname"] ?>"/>
            </p>

            <?php
                if ($employee_form_error != null) {
                    if (in_array("employee_pass_empty", $employee_form_error)) echo "<p class='error'>Please fill in Employee Password</p>";
                    elseif (in_array("employee_pass_invalid", $employee_form_error)) echo "<p class='error'>Password must be at least 5 characters long and include a number</p>";
                }
            ?>
            <p class="row">	
                <label for="emp_pass">Employee Password: </label>
                <input type="password" name="emp_pass" id="emp_pass" value=""/>
            </p>
		
	    <p class="row">	
		<label for="role">Role: </label> 
		<select id="role" name="role">
		    <option value="employee">Employee</option>
		    <option value="manager">Manager</option>
		</select>
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
<?php
}}
?>
