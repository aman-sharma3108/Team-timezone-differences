<?php
echo "
<div class='nav'>
    <a href='productform.php'>Add Product</a></li>
    <a href='editproductform.php'>Edit Product</a></li>
    <a href='salesform.php'>Add Sale</a></li>
    <a href='editsalesform.php'>Edit Sale</a></li>
    <div class='dropdown'>   
        <button id='dropbtn' class='dropbtn'>View <i class='fa fa-caret-down'></i></button>
            <div id='myDropdown' class='dropdown-content'>
                <a href='viewproduct.php'>All Products</a>
                <a href='viewsale.php'>All Sales</a>
                <a href='viewsaledetails.php'>All Sales Details</a>
            </div>   
    </div>";
    if ($_SESSION["Login"]==true && $_SESSION["Role"]=="manager") {
        echo    " <a href='salessummary.php'>Sales Summary</a></li>
                <a href='prediction.php'>Sales Prediction</a></li>
                <a href='readcsv.php'>Read CSV</a></li>
                <a href='employeeform.php'>Add Employee</a></li>";
  
    }
echo "<a id='logout' href='logout.php'>Log Out</a>
</div>";
?>
