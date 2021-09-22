<!DOCTYPE html>
<html lang="en">
<head>
<title>Sales</title>

<meta charset="utf-8" />
<meta name="description" content="Product form"  />
<meta name="keywords" content="Form, Input" />
<link href= "styles/style.css" rel="stylesheet"/>
<!-- Description: Form Input for sale -->
<!-- Author: Calvin Bell -->
<!-- Date: 08/09/21 -->

</head>
<body>
    <?php  
        include "./includes/header.inc";
        include "./includes/nav.inc";
    ?>

	<h1>Adding a sale</h1>

	<form method="POST" action="http://mercury.swin.edu.au/it000000/formtest.php">
	<fieldset>
        <legend>Current Sale</legend>
            <p class="row">
                <label for="saleid">Sale ID: </label>
                <input type="text" name="saleid" id="saleid" />
            </p>
            <p>
                <label for="dos">Date of Sale</label> 
                <input type="date" name= "dos" id="dos" placeholder="dd-mm-yyyy" maxlength="10" size="10"/>
            </p>
            <p>
                <label for="appt">Time of Sale:</label>
                <input type="time" id="appt" name="appt">
            </p>
            <p class="row">	
                <label for="productname">Product(s) Name: </label>
                <form class="example" action="/action_page.php" style="margin:auto;max-width:300px">
                <input type="text" placeholder="Search.." name="search2">
                <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </p>
            <p>
                <label for="qty">Quantity</label>
                <input type="text" id="qty" name="qty" maxlength="4" size="2" />
            </p>
            <p class="row">	<label for="tprice">Total Price: </label>
                <input type="text" name="tprice" id="tprice" />
            </p>
            <p class="row">	
                <label for="empid">Employee ID: </label>
                <input type="text" name="empid" id="empid" />
            </p>
            <p>
                <input type="submit" value="Complete sale" />
            </p>
	</fieldset>
	</form>

    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>