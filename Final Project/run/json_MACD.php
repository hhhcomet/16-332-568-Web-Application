<!-- Written by Xiuqi YE
     Assisted by Jingxuan Chen
     Debugged by Rong Zhang -->
<?php  
function json_MACD(){
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

$sql="SELECT distinct Symbol FROM HistoricalPrices";
$result=mysql_query($sql);

while ($name=mysql_fetch_assoc($result)){
	
$sql="SELECT Date,DIF,DEM,MACD FROM HistoricalPrices WHERE Symbol=\"{$name['Symbol']}\" ORDER BY `Date` ASC";  
$countt=mysql_query("SELECT count(*) FROM HistoricalPrices WHERE Symbol=\"{$name['Symbol']}\" ");
$countt=mysql_fetch_assoc($countt);
//echo $countt['count(*)'];
$result2 = mysql_query($sql);  
if ($result2)
{
    $i=0; 
	$fp=fopen("json/MACD/{$name['Symbol']}.json","w");
	fwrite($fp,"[");
    for ($i=0;$i<$countt['count(*)'];$i++)
    { 
		$row=mysql_fetch_assoc($result2);
		$year=((int)substr($row['Date'],0,4));
		$month=((int)substr($row['Date'],5,2));
		$day=((int)substr($row['Date'],8,2));
			$row['Date']=mktime(0,0,0,$month,$day,$year).'000';
		$str="[".$row['Date'].",".$row['DIF'].",".$row['DEM'].",".$row['MACD']."],";
		if ($i!=$countt['count(*)']-1) $str="[".$row['Date'].",".$row['DIF'].",".$row['DEM'].",".$row['MACD']."],";
		else  $str="[".$row['Date'].",".$row['DIF'].",".$row['DEM'].",".$row['MACD']."]";
			fwrite($fp,$str);
			fwrite($fp,"\n");
    } 
	        
}
   
  fwrite($fp,']');
fclose($fp);
}   


mysql_close($con);

}
?>  