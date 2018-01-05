<?php
//written by :Xiuqi Ye
// Debugged by Feng Rong
//assisted by Xiaoyi Tang

include_once "connect.php";
//create database connection object
$host = "localhost";
$user = "root";

$conn = new mysqli ($host, $user);

if ($conn->connect_error) {
    die("Fail." . $conn->connect_error);
} 

// create database
$sql = "CREATE DATABASE StockDatabase";
if ($conn->query($sql) === TRUE) {
    echo "Success.";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();

$dbConnect = new dbConnect();
$dbConnect->connect();

//creat Historical Prices Table
$dbConnect->prepare("CREATE TABLE StockDatabase.HistoricalPrices (Symbol VARCHAR(11) NOT NULL, Date VARCHAR(20) NOT NULL, Open DECIMAL(7,2) NOT NULL, High DECIMAL(7,2) NOT NULL, Low DECIMAL(7,2) NOT NULL,Close DECIMAL(7,2) NOT NULL, Volume INT(11) NOT NULL, AdjClose DECIMAL(7,2) NOT NULL, K DECIMAL(5,2), D DECIMAL(5,2), J DECIMAL(5,2), EMA12 DECIMAL(5,2), EMA26 DECIMAL(5,2),DIF DECIMAL(5,2), DEM DECIMAL(5,2), MACD DECIMAL(5,2), VR DECIMAL(11,10))");
$dbConnect->execute();

//creat RealTime Prices Table
$dbConnect->prepare("CREATE TABLE StockDatabase.RealTimePrice (Symbol VARCHAR(11) NOT NULL, Date VARCHAR(20) NOT NULL, Time VARCHAR(10) NOT NULL, Price DECIMAL(7,2) NOT NULL, Volume INT(11) NOT NULL)");
$dbConnect->execute();

$dbConnect->prepare("CREATE TABLE StockDatabase.UserInfo (Email VARCHAR(30) NOT NULL, username VARCHAR(15) NOT NULL, password VARCHAR(15) NOT NULL, stock VARCHAR(10), eprice DECIMAL(7,2))");
$dbConnect->execute();
//disconnected from database
$dbConnect->disconnect();

exit();

?>