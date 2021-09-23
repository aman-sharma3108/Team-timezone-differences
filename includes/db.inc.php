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
		SaleDate DATE NOT NULL,
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
	);";

	mysqli_multi_query($connection, $query);

	mysqli_close($connection);
}

$connection = mysqli_connect($servername, $username, $password, $database); //create connection to server
?>
