<!-- written by Feng Rong
     debugged by Xiuqi Ye
     assisted by Rong Zhang-->
<?php
include_once 'classes/dbConnection.php';
	
	//create data base connection object
	$dbConnection = new dbConnection();
	//connect to database
	$dbConnection->connect();
	
	//create StockForecasting Database
	$dbConnection->prepare("CREATE DATABASE StockForecasting");
	$dbConnection->execute();
	
	//create Historical Prices Table
	$dbConnection->prepare("CREATE TABLE StockForecasting.HistoricalPrices (StockID INT(11) NOT NULL, Date DATE NOT NULL, Open DECIMAL(7,2) NOT NULL, High DECIMAL(7,2) NOT NULL, Low DECIMAL(7,2) NOT NULL, Close DECIMAL(7,2) NOT NULL, Volume INT(11) NOT NULL, AdjClose DECIMAL(7,2) NOT NULL)");
	$dbConnection->execute();
	
	//create Stocks Table
	$dbConnection->prepare("CREATE TABLE StockForecasting.Stocks (StockID INT(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY(StockID), Ticker VARCHAR(5) NOT NULL, Company VARCHAR(30) NOT NULL, Price DECIMAL(7,2) NOT NULL, Exchange VARCHAR(20) NOT NULL, Time INT(10) NOT NULL)");
	$dbConnection->execute();
	
	//Create Keywords Table
	$dbConnection->prepare("CREATE TABLE StockForecasting.Keywords (Keyword VARCHAR(30) NOT NULL, Count INT(11) NOT NULL)");
	$dbConnection->execute();
	
	//create Predictions Table
	$dbConnection->prepare("CREATE TABLE StockForecasting.Predictions (StockID INT(11) NOT NULL, Date DATE NOT NULL, PredictedPrice DECIMAL(7,2) NOT NULL, ConfidenceValue DECIMAL(7,2) NOT NULL, PredictedDecision VARCHAR(10) NOT NULL, Gain DECIMAL(7,2) NOT NULL, WaitTime INT(11) NOT NULL)");
	$dbConnection->execute();
	
	//create Prediction Times table
	$dbConnection->prepare("CREATE TABLE StockForecasting.PredictionTimes (Date DATE NOT NULL, Time DECIMAL(7,3) NOT NULL)");
	$dbConnection->execute();
	
	//disconnect from database
	$dbConnection->disconnect();
	
	exit();
	
?>