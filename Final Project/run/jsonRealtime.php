<!-- Written by Rong Zhang
     Assisted by Xiuqi Ye
     Debugged by Jingxuan Chen -->
<?php 
date_default_timezone_set('America/New_York');
function jsonRealtime(){


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

$sql="SELECT distinct Symbol FROM RealTimePrice";
$result=mysql_query($sql);
while ($row=mysql_fetch_assoc($result))
{
    //echo $row['Symbol'];
    $today=strtotime("Today");
    $date1=date("Y/m/d",$today);
    //echo $date1;
    $sql="SELECT Time,Price FROM RealTimePrice WHERE Symbol=\"{$row['Symbol']}\" AND Date=\"{$date1}\" ORDER BY `Time` ";  
    $result2 = mysql_query($sql);   

    $countt=mysql_query("SELECT count(*) FROM RealtimePrice WHERE Symbol=\"{$row['Symbol']}\" AND Date=\"{$date1}\" ");
    $countt=mysql_fetch_assoc($countt);
    if ($result2)
    {
        $fp=fopen("json/Realtime/{$row['Symbol']}_realtime.json","w");
        fwrite($fp,"[");
        for($i=0;$i<$countt['count(*)'];$i++){
            $row2=mysql_fetch_assoc($result2);
            $Utime=strtotime("Today ".$row2['Time']);
            $Utime=$Utime-4*60*60;
            $Utime=((string)intval($Utime))."000";
            $str="[".$Utime.",".$row2['Price']."],";
            if($i==$countt['count(*)']-1)
                $str="[".$Utime.",".$row2['Price']."]";
            fwrite($fp,$str);
            fwrite($fp,"\n");
        }
		fwrite($fp,']');				
        fclose($fp);
    }   
	
}
    


mysql_close($con);
    
}
  
?>  