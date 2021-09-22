<!DOCTYPE html>
<html lang="en">
<head>
<title>View Details</title>

<meta charset="utf-8" />
<meta name="description" content="Product form"  />
<meta name="keywords" content="Form, Input" />
<link href= "styles/style.css" rel="stylesheet"/>

</head>
<body>
    <?php
        include "./includes/header.inc";
        include "./includes/nav.inc";
    ?>

    <form method="post" action="viewproduct.php">
        <p>
        <input type="submit" value="View All Products" />
        </p>
    </form>

    <form method="post" action="viewsale.php">
        <p>
        <input type="submit" value="View All Sales" />
        </p>
    </form>

    <?php
        include "./includes/footer.inc";
    ?>
</body>
</html>