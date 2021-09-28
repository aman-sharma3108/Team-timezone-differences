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
    </div>
    <a id='logout' href='logout.php'>Log Out</a>
</div>";
?>
