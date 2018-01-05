<!-- written by Jingxuan Chen
     debugged by Xiuqi YE
     assisted by Zhe Chang -->
<?php
class MACD{
public function predict_MACD($Stockname){

// get data from database	
 $con=mysql_connect("localhost","root","");
 if (!$con)
 {
   die("Could not connect: " . mysql_error());
 }
 $db=mysql_select_db("stockdatabase", $con);
 if(!$db)
 {
   die("Failed to connect MySQL:". mysql_error());
 }

//count the rows of data
  $sql="SELECT COUNT(*) FROM HistoricalPrices WHERE '$Stockname'=Symbol";
    $row=mysql_fetch_array(mysql_query($sql));
echo "row= ",$row[0],"<br>";
for ($da=$row[0]-1;$da>$row[0]-2;$da--){
//select the closing price of the latest day 
$sql = "SELECT Close FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$C=mysql_result($query,0);

//select  the latest day 
$sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$d1=mysql_result($query,0);

//select  yesterday's EMA(12) 
$sql = "SELECT EMA12 FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$YE12=mysql_result($query,0);


//calculate EMA(12)
$E12=$C;
//calculate EMA(26)
$E26=$C;
//calculate DIF
$DIF=$E12-$E26;
//calculate DEM
$DEM=$DIF;
//calculate MACD
$MACD=($DIF-$DEM)*2;
//updatate data into historicaldatabase
$sql = "UPDATE HistoricalPrices SET EMA12={$E12}, EMA26={$E26}, DIF={$DIF}, DEM={$DEM}, MACD={$MACD} WHERE Date='{$d1}'AND '$Stockname'=Symbol";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());

}
 for ($da=$row[0]-2;$da>=0;$da--){
$db=$da+1;

//select the closing price of the latest day 
$sql = "SELECT Close FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$C=mysql_result($query,0);

//select  the latest day 
$sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$d1=mysql_result($query,0);

//select  yesterday's EMA(12) 
$sql = "SELECT EMA12 FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $db,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$YE12=mysql_result($query,0);

//select  yesterday's EMA(26) 
$sql = "SELECT EMA26 FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $db,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$YE26=mysql_result($query,0);

//select  yesterday's DEA 
$sql = "SELECT DEM FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $db,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$YDEM=mysql_result($query,0);

//calculate EMA(12)
$E12=$YE12*11/13+$C*2/13;
//calculate EMA(26)
$E26=$YE26*25/27+$C*2/27;
//calculate DIF
$DIF=$E12-$E26;
//calculate DEM
$DEM=$YDEM*8/10+$DIF*2/10;
//calculate MACD
$MACD=($DIF-$DEM)*2;
//updatate data into historicaldatabase
$sql = "UPDATE HistoricalPrices SET EMA12={$E12}, EMA26={$E26}, DIF={$DIF},DEM={$DEM}, MACD={$MACD} WHERE Date='{$d1}'AND '$Stockname'=Symbol";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
}


}


public function Analysis_MACD($Stockname){

//select  EMA(12) 
$sql = "SELECT EMA12 FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$E12=mysql_result($query,0);

//select  EMA(26) 
$sql = "SELECT EMA26 FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$E26=mysql_result($query,0);

//select DEA 
$sql = "SELECT DEM FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$DEM=mysql_result($query,0);

//select yesterday's DEM
$sql = "SELECT DEM FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 1,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$YDEM=mysql_result($query,0);

//select yesterday's DIF
$sql = "SELECT DIF FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 1,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$YDIF=mysql_result($query,0);


//calculate DIF
$DIF=$E12-$E26;
//calculate MACD
$MACD=($DIF-$DEM)*2;

echo "EMA(12)= ",$E12,"<br>";
echo "EMA(26) = ",$E26,"<br>";
echo "DIF = ",$DIF,"<br>";
echo "DEM = ",$DEM,"<br>";

if ($DIF>0&&$DEM>0&&$DIF>$DEM&&$YDIF<$YDEM)
{
	echo "Suggestion: Buy";
}
else if ($DIF<0&&$DEM<0&&$DIF<$DEM&&$YDIF>$YDEM)
{
	echo "Suggestion: Sell";
}else 
{
	echo "Suggestion: Hold";
}
}
}
?>