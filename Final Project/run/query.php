<?php
//written by: Zhe Chang
//Asissted by: Xiaoyi Tang
// Debugged by Feng Rong 

	class query {
		
		//insert into the historical prices table
		public function insert_historical($name) {
			return "INSERT INTO HistoricalPrices (Symbol, Date, Open, High, Low, Close, Volume,adjclose) VALUES ( ?, STR_TO_DATE(?,'%Y-%m-%d'), ?, ?, ?, ?, ?,?)";
		}
		
		//get all stockIDs and tickers
		public function get_stockID_ticker_name() {
			return "SELECT Symbol FROM stockdatabase";
		}
		
        //get last insert date for a given stockID
		public function get_last_date() {
			return "SELECT  MAX(Date) AS recentDate from HistoricalPrices GROUP BY 'date'";
		}
		
		public function update_realtime($name) {
			return "INSERT INTO HistoricalPrices (Symbol, date, time, price, volume) VALUES ( ?, ?, ?, ?, ?)";
		}

	}
?>