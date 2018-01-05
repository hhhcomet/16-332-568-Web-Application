<!-- written by Xiuqi Ye
     Debugged by Jinguan Chen
     assisted by Feng Rong -->
<?php
set_time_limit(150);
class VR{
	public function predict_VR($Stockname){
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
    $sql="SELECT COUNT(*) FROM HistoricalPrices WHERE '$Stockname'=Symbol ";
    $row=mysql_fetch_array(mysql_query($sql));
    //echo $row;
    $sql = "UPDATE HistoricalPrices SET VR=0.00 WHERE'$Stockname'=Symbol";
    $query=mysql_query($sql) or die($query."<br/><br/>".mysql_error()); 


     for ($da=0;$da<$row[0];$da++){
    //select  the latest day 
    $sql = "SELECT Date FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT $da,1";
    $query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
    $d1=mysql_result($query,0);	
	$data=mysql_query("SELECT Open,Close,Volume FROM HistoricalPrices WHERE '$Stockname'=Symbol ORDER BY `Date` DESC LIMIT $da,24");
	$AVS=$BVS=$CVS=0;
	while($day=mysql_fetch_assoc($data))
	{
		if ($day['Close']>$day['Open']) $AVS=$AVS+$day['Volume'];
		if ($day['Close']<$day['Open']) $BVS=$BVS+$day['Volume'];
		if ($day['Close']==$day['Open']) $CVS=$CVS+$day['Volume'];
	}
    if (($BVS+1/2*$CVS)==0)
        $VR=$AVS+1/2*$CVS;
    else
        $VR=($AVS+1/2*$CVS)/($BVS+1/2*$CVS);
	
	$sql = "UPDATE HistoricalPrices SET VR={$VR} WHERE Date='{$d1}'AND '$Stockname'=Symbol";
    $query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
    }
	}
	public function Analysis_VR($Stockname){
		$sql = "SELECT VR FROM HistoricalPrices WHERE '$Stockname'=Symbol LIMIT 1";
        $query=mysql_query($sql) or die($query."<br/><br/>".mysql_error());
        $VR=mysql_result($query,0);
        echo "VR= ", $VR,"<br>";
		if ($VR<0.7&& $VR>0.4) echo "Suggestion: Buy.";
		else if ($VR>=0.7 && $VR<1.6) echo "Suggestion: Hold.";
		else if ($VR>=1.6) echo "Suggestion: Sell.";
		else echo "Unable to give suggestion"; 

	}
}
?>
