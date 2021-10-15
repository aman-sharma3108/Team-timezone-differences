<?php
/*
File Name: db.inc.php
Date: 8/9/2021
Author: Khai Tran
Purpose: Database Connection
*/

$servername = "localhost";
$username = "root";
$password = "";

/*
//Connect to mariaDB server
//Server Name
$servername = "feenix-mariadb.swin.edu.au";

//Username: letter 's' followed by your student ID
$username = "s102895680"; 

//Password: password of your mariaDB - Initially this will be your six digit date of birth in DDMMYY format (not sure if you have ever changed it tho)
$password = "121200"; 
*/

$database = "php_sreps";

$connection = mysqli_connect($servername, $username, $password); //create connection to server

$selected_database = mysqli_select_db($connection, $database); //select database

if (!$selected_database) //check if the database not exists
{
    //create database
    $query = "CREATE DATABASE IF NOT EXISTS ".$database;
    mysqli_query($connection, $query);
}

$connection = mysqli_connect($servername, $username, $password, $database); //create connection to server

if ($connection) {
	$hashed_pass = password_hash('admin', PASSWORD_DEFAULT); //hash password to increase security
	//create tables
	$query = "CREATE TABLE IF NOT EXISTS products(
		ProductID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
		ProductName TINYTEXT NOT NULL,
		Category TINYTEXT NOT NULL,
		Price FLOAT(10,2) NOT NULL,
		Comments TINYTEXT,
		Stock INT
	);
	
	CREATE TABLE IF NOT EXISTS sales(
		SaleID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
		SaleDateTime DATETIME NOT NULL,
		PriceTotal FLOAT(10,2) NOT NULL,
		EmployeeID INT NOT NULL
	);
	
	CREATE TABLE IF NOT EXISTS productsalelinks(
		SaleID INT NOT NULL,
		ProductID INT NOT NULL,
		Quantity INT NOT NULL,
		SubTotal FLOAT(10,2) NOT NULL,
		FOREIGN KEY (SaleID) references sales(SaleID),
		FOREIGN KEY (ProductID) references products(ProductID)
	);
	
	CREATE TABLE IF NOT EXISTS users(
		UserID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
		Username VARCHAR(30) NOT NULL,
		Firstname VARCHAR(30) NOT NULL,
		Lastname VARCHAR(30) NOT NULL,
        Password longtext NOT NULL,
		Role VARCHAR(30) NOT NULL    
	);
	
	INSERT INTO users (Username, Firstname, Lastname, Password, Role)
	SELECT * FROM (SELECT 'admin' AS Username, 'Administrator' AS Firstname, 'Manager' AS Lastname, '$hashed_pass' AS Password, 'manager' AS Role) AS tmp
	WHERE NOT EXISTS (
		SELECT Username FROM users WHERE Username = 'admin'
	) LIMIT 1;";

	mysqli_multi_query($connection, $query);
	mysqli_close($connection);
}

$connection = mysqli_connect($servername, $username, $password, $database); //create connection to server
?>
