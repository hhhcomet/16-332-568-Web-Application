<?php
//Written by: FengRong
//Debugged by Rong Zhang
//Assisted by Jingxuan Chen
    set_time_limit(0);
    include_once 'SendEmail.php';
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
    
    $interval = 60000000;
    while(true)
    {
    $userset=mysql_query("SELECT distinct username FROM UserInfo");
    while($username=mysql_fetch_row($userset))
    {
        $i=1;
        $query = mysql_query("SELECT username, Email, stock, eprice FROM UserInfo WHERE username='$username[0]'");
        $row_number   = mysql_num_rows($query);
        if(empty($row_number))
        {
            exit;
        }
        while ($row=mysql_fetch_row($query))
        {
            $today=strtotime("Today");
            $date1=date("Y/m/d",$today);
            $time= mysql_query("SELECT MAX(time) FROM RealtimePrice WHERE Symbol=\"{$row[2]}\" AND Date=\"{$date1}\"");
            $time=mysql_result($time,0);
            $query2 = mysql_query("SELECT Price FROM RealtimePrice WHERE Symbol=\"{$row[2]}\" AND Time=\"{$time}\"");
            $price =  mysql_result($query2,0);
            echo $row[3];
            if ($price<=$row[3]){
                sendemail($row[0],$row[1],$row[2],$row[3],$price);
                //$query3 = mysql_query("DELETE FROM UseInfo WHERE username='$row[0]' AND Email='$row[1]' AND symbol='$row[2]' AND Open=$row[3]");
                echo "haha";
            }
        }
        
    }
    usleep($interval);
    }

    ?>
