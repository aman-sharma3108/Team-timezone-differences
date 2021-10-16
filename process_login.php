<?php 

session_start(); 

include "./includes/db.inc.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $uname = validate($_POST['uname']);

    $pass = validate($_POST['password']);

    if (empty($uname)) {

        header("Location: login.php?error=User Name is required");

        exit();

    }else if(empty($pass)){

        header("Location: login.php?error=Password is required");

        exit();

    }else{

        //check if the username is in the database
        $sql = "SELECT * FROM users WHERE Username=?;"; //prepare statement
        $statement = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($statement, $sql)) //check if the database is failed
        {
            header ("Location: login.php?error=sqlerror"); //return to the Login page with a message that the database is failed
            exit();
        }
        else //if the database is not failed
        {
            mysqli_stmt_bind_param($statement, "s", $uname); //insert data to statement
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement); //store the result
            if ($arrResult = mysqli_fetch_assoc($result)) //check if the username exists and then fetch it as an associative array
            {
                $booPasswordCheck = password_verify($pass, $arrResult['Password']);
                if ($booPasswordCheck == false) //if password is wrong
                {
                    header ("Location: login.php?error=Incorrect User name or password"); //return to the Login page with a message that the password is wrong
                    exit(); 
                }
                else if($booPasswordCheck == true) //if password is right
                {
                    header ("Location: index.php"); //go the the Table Management page
                    $_SESSION["Login"] = true; //global variable to say that the software is logged in

                    $_SESSION['Username'] = $arrResult['Username'];

                    $_SESSION['Firstname'] = $arrResult['Firstname'];

                    $_SESSION['UserID'] = $arrResult['UserID'];

                    $_SESSION["Role"] = $arrResult["Role"];
                    exit(); 
                }
            }
            else //if the username does not exist
            {
                header ("Location: index.php?error=Incorrect User name or password"); //return to the Login page with a message that the username does not exist
                exit();
            }
        }

        // $sql = "SELECT * FROM users WHERE Username='$uname' AND Password ='$pass'";

        // $result = mysqli_query($connection, $sql);

        // if (mysqli_num_rows($result) === 1) {

        //     $row = mysqli_fetch_assoc($result);

        //     if ($row['Username'] === $uname && $row['Password'] === $pass) {

        //         echo "Logged in!";

        //         $_SESSION['Username'] = $row['Username'];

        //         $_SESSION['Firstname'] = $row['Firstname'];

        //         $_SESSION['UserID'] = $row['UserID'];

        //         $_SESSION["Role"] = $row["Role"];

        //         header("Location: index.php");

        //         exit();

        //     }else{

        //         header("Location: login.php?error=Incorrect User name or password");

        //         exit();

        //     }

        // }else{

        //     header("Location: index.php?error=Incorect User name or password");

        //     exit();

        // }

    }

}else{

    header("Location: index.php");

    exit();

}
