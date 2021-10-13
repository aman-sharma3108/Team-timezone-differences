<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Digital Sales System" />
    <meta name="keywords" content="Sales, System" />
    <meta name="author" content="Team Timezone Differences"  />
    <title>LOGIN</title>
    <link href= "styles/styles1.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>

</head>

<body>
    <section>
        <p>
            <form action="process_login.php" method="post">
                <h2>LOGIN</h2>

<?php if (isset($_GET['error'])) { ?>

    <p class="error"><?php echo $_GET['error']; ?></p>

<?php } ?>

                <label>User Name</label>
                <input type="text" name="uname" placeholder="User Name"><br>
                <label>Password</label>

                <input type="password" name="password" placeholder="Password"><br> 
                 
                <button type="submit">Login</button>

            </form>
        </p>
    </section>

<?php
    include "./includes/footer.inc";
?>
</body>
</html>
