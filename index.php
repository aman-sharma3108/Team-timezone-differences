<?php 

session_start();

if (isset($_SESSION['UserID']) && isset($_SESSION['Username'])) {

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="description" content="Digital Sales System" />
  <meta name="keywords" content="Sales, System" />
  <meta name="author" content="Team Timezone Differences"  />
  <title>Homepage</title>
  <link href= "styles/style.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
<script src="js/scripts.js"></script>
  <!-- <script src="scripts/seminarinput.js"></script> -->
</head>

<body>
    <?php
        include "./includes/header.inc";
        include "./includes/nav.inc";
    ?>

    <section>
        <h2>Hello, <?php echo $_SESSION['Firstname']; ?>. Welcome To The Digital Catalogue System.</h2>
        <p>
            Current Page: Home
        </p>
        <p>
            Products: Add a new product to the Catalogue
        </p>
        <p>
            Sales: Add a new sales to the database
        </p>
        <p>
            Edit Products: Edit a product's details from the catalogue
        </p>
        <p>
            Edit Sales: Edit a sale record from the database
        </p>
        <p>
            View: View products and sales in the database
        </p>
        <p>
            Query: Query items in the database
        </p>
    </section>

    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>

<?php 

}else{

     header("Location: login.php");

     exit();

}

 ?>