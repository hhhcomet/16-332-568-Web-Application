<!-- written by Xiuqi Ye
     debugged by Jingxuan Chen
     assisted by Xiaoyi Tang -->
<?php
class KDJ{
public function predict_KDJ($Stockname){

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

//insert the initial values of K,D,J
     $sql="SELECT COUNT(*) FROM HistoricalPrices WHERE '$Stockname'=Symbol ";
    $row=mysql_fetch_array(mysql_query($sql));
    //echo $row;
    //$a=($row-8);
    //$b=$row-16;


 for ($dd=$row[0]-1;$dd>$row[0]-2;$dd--)
 {

$sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $dd,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$d1=mysql_result($query,0);

$sql = "UPDATE HistoricalPrices SET D=50, K=50 ,J=0.00 WHERE Date='{$d1}'AND '$Stockname'=Symbol";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());

}

 for ($da=$row[0]-2;$da>$row[0]-10;$da--){
$db=$da+1;

//select the closing price of the latest day 
$sql = "SELECT Close FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$C=mysql_result($query,0);

//select  the latest day 
$sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$d1=mysql_result($query,0);

//select the highest price during the last 9 days
$sql = "SELECT High FROM HistoricalPrices WHERE Date='$d1' AND '$Stockname'=Symbol ";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$H9=mysql_result($query,0);

//select the lowest price during the last 9 days
$sql = "SELECT Low FROM HistoricalPrices WHERE Date='$d1' AND '$Stockname'=Symbol ";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$L9=mysql_result($query,0);

//set K8 value
$sql = "SELECT K FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $db,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$D8=mysql_result($query,0);
//set D8 value
$sql = "SELECT D FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $db,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$K8=mysql_result($query,0);
//caculate RSV9
$RSV9=($C-$L9)/($H9-$L9)*100;
//calculate K9
$K9=2/3*$K8+1/3*$RSV9;             
//calculate D9
$D9=2/3*$D8+1/3*$K9;               
//calculate J9
$J9=3*$K9-2*$D9;

$sql = "UPDATE HistoricalPrices SET D={$D9}, K={$K9}, J={$J9} WHERE Date='{$d1}'AND '$Stockname'=Symbol";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
}

 for ($da=$row[0]-10;$da>=0;$da--){
$db=$da+8;
$dc=$da+1;
//select the closing price of the latest day 
$sql = "SELECT Close FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$C=mysql_result($query,0);

//select  the latest day 
$sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$d1=mysql_result($query,0);

//select the last 9th day
$sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $db,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$d9=mysql_result($query,0);

//select the highest price during the last 9 days
$sql = "SELECT MAX(High) FROM HistoricalPrices WHERE Date>='$d9' AND '$Stockname'=Symbol ";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$H9=mysql_result($query,0);

//select the lowest price during the last 9 days
$sql = "SELECT MIN(Low) FROM HistoricalPrices WHERE Date>='$d9' AND '$Stockname'=Symbol ";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$L9=mysql_result($query,0);

//set K8 value
$sql = "SELECT K FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $dc,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$D8=mysql_result($query,0);
//set D8 value
$sql = "SELECT D FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $dc,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$K8=mysql_result($query,0);
//caculate RSV9
$RSV9=($C-$L9)/($H9-$L9)*100;
//calculate K9
$K9=2/3*$K8+1/3*$RSV9;             
//calculate D9
$D9=2/3*$D8+1/3*$K9;               
//calculate J9
$J9=3*$K9-2*$D9;

$sql = "UPDATE HistoricalPrices SET D={$D9}, K={$K9}, J={$J9} WHERE Date='{$d1}'AND '$Stockname'=Symbol";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
}


}
public function Analysis_KDJ($Stockname){

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

//select the closing price of the latest day 
$sql = "SELECT Close FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$C=mysql_result($query,0);

//select  the latest day 
$sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$d1=mysql_result($query,0);

//select the last 9th day
$sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 8,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$d9=mysql_result($query,0);

//select the highest price during the last 9 days
$sql = "SELECT MAX(High) FROM HistoricalPrices WHERE Date>='$d9' AND '$Stockname'=Symbol ";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$H9=mysql_result($query,0);

//select the lowest price during the last 9 days
$sql = "SELECT MIN(Low) FROM HistoricalPrices WHERE Date>='$d9' AND '$Stockname'=Symbol ";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$L9=mysql_result($query,0);

//set K9 value
$sql = "SELECT K FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 8,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$D9=mysql_result($query,0);
//set D9 value
$sql = "SELECT D FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 8,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$K9=mysql_result($query,0);
//set J9 value
$sql = "SELECT J FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 8,1";
$query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
$J9=mysql_result($query,0);

//caculate RSV9
$RSV9=($C-$L9)/($H9-$L9)*100;
            
echo "RSV= ",$RSV9,"<br>";
echo "K = ",$K9,"<br>";
echo "D = ",$D9,"<br>";
echo "J = ",$J9,"<br>";

if ($K9<20&&$D9<20&&$J9<20)
{
	echo "Suggestion: Buy";
}
else if ($K9>80&&$D9>80&&$J9>80)
{
	echo "Suggestion: Sell";
}else 
{
	echo "Suggestion: Hold";
}
}
}
?>
