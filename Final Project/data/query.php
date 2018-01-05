<?php
//written by :FengRong
//assisted by Jingxuan Chen
//debugged by Zhe Chang
class query
{	
	//insert historical price
	public function insert_historical()
	{
		return "INSERT INTO HistoricalPrices (Symbol, Date, Open, High, Low, Close, Volume, AdjClose) VALUES (?,?,?,?,?,?,?,?)";
	}

	
	//delete historical prices
	public function delete_stock_HistoricalPrice()
	{
		return "DELETE FROM HistoricalPrices WHERE Symbol=?";
	}
	//insert real-time price
	public function insert_real_time()
	{
		return "INSERT INTO RealTimePrice (Symbol, Date, Time, price, Volume) VALUES (?,?,?,?,?)";
	}
	//get last insert data for a given symbol
	public function get_last_date()
	{
		return "SELECT MAX(Date) AS recentDate from HistoricalPrices GROUP BY Symbol";
	}

}


?>


