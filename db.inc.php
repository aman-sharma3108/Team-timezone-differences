<?php
/*
File Name: db.inc.php
Date: 8/9/2021
Author: Khai Tran
Purpose: Database Connection
*/

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "php_sreps";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword); //create connection to server

$dbSelected = mysqli_select_db($conn, $dbname); //select database

if (!$dbSelected) //check if the database not exists
{
    //create database
    $strSQL = "CREATE DATABASE IF NOT EXISTS ".$dbName;
    mysqli_query($conn, $strSQL);
}

$strSQL = "CREATE TABLE IF NOT EXISTS products(
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
	NumberofProducts INT NOT NULL,
	PriceTotal FLOAT(10,2) NOT NULL,
	EmployeeID INT NOT NULL
);
CREATE TABLE IF NOT EXISTS productsalelinks(
	SaleID INT NOT NULL,
	ProductID INT NOT NULL,
	FOREIGN KEY (SaleID) references sales(SaleID),
	FOREIGN KEY (ProductID) references products(ProductID)
);"
mysqli_query($conn, $strSQL);

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName); //create connection to server
?>
