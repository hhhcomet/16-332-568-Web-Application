<?php

//written by: Xiaoyi Tang
//Assisted by: Zhe Chang
//Debug by: Zhe Chang

class search{
	public function connect(){
	$con = @mysql_connect("localhost","root","");
	if (!$con){
		die("Could not connect: " . mysql_error());
	}
	$db = mysql_select_db("stockdatabase",$con);
	if(!db){
		die("Fail to connect MySQL:" . mysql_error());
	}
	}

	public function findhighest($name){
		$date = date('Y/m/d', strtotime("-10days",strtotime(date('Y/m/d'))));
		$query=mysql_query("SELECT MAX(AdjClose) FROM StockDatabase.HistoricalPrices WHERE date>='$date' AND '$name'=Symbol");
		$highest = mysql_result($query, 0);
		return $highest;
	}

	public function findaverage($name){
		$date = date('Y/m/d', strtotime("-1year",strtotime(date('Y/m/d'))));
		$query=mysql_query("SELECT AVG(AdjClose) FROM HistoricalPrices WHERE date>='$date' AND Symbol='$name'");
		$avg = mysql_result($query, 0);
		return $avg;
	}

	public function findlowest($name){
		$date = date('Y/m/d', strtotime("-1year",strtotime(date('Y/m/d'))));
		$query=mysql_query("SELECT MIN(AdjClose) FROM StockDatabase.HistoricalPrices WHERE date>='$date' AND '$name'=Symbol");
		$lowest = mysql_result($query, 0);
		return $lowest;
	}

	public function findaveragelow($name)
	{
    	$date = date('Y/m/d', strtotime("-1year", strtotime(date('Y/m/d'))));
		$query=mysql_query("SELECT Symbol FROM StockDatabase.HistoricalPrices WHERE date>='$date' AND (MIN(AdjClose)>ANY(SELECT AVG(AdjClose) FROM StockDatabase.HistoricalPrices WHERE date>='$date'))");
		$averagelow = mysql_result($query, 0);
		return $averagelow;
	} 
}