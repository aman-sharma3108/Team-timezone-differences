<?php
require_once ("./includes/db.inc.php");
$product_id = $_REQUEST['product_id'];
  
  
if ($product_id !== "") {
      
    $query = mysqli_query($connection, "SELECT ProductName, 
    Category, Price, Comments, Stock FROM products WHERE ProductID='$product_id'");
  
    $row = mysqli_fetch_array($query);

    $product_name = $row["ProductName"];
    $category = $row["Category"];
    $price = $row["Price"];
    $comments = $row["Comments"];
    $stock = $row["Stock"];
}
  
// Store it in a array
$result = array("$product_name", "$category", "$price", "$comments", "$stock");
  
// Send in JSON encoded form
$myJSON = json_encode($result);
echo $myJSON;
?>