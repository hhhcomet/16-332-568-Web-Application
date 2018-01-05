<?php
//written by RongZhang
//debugged by Jingxuan Chen
//assisted by Feng Rong 
    include_once "query.php";
    include_once "connect.php";
    include_once "getHistory.php";
    include_once "predict_VR.php";
    include_once "predict_KDJ.php";
    include_once "predict_MACD.php";
    include_once "download.php";
    date_default_timezone_set('America/New_York');

    $connection=new dbConnect();//dbconnect object
    $query = new query();//query object
    $getHistory= new GetHistory();//gethistory object
    $connection->connect();
    $vr = new VR();
    $KDJ = new KDJ();
    $MACD= new MACD();
    $stocksymbol=array("GOOG","YHOO","MSFT","GE","PTR","IBM","SNGX","ISRG","EGT","AAPL");
    $day = date("d");
    $month = date("m");
    $year = date("Y");
    foreach ($stocksymbol as $tmp) 
    {
    downfile($tmp, $day, $month, $year);
    }

    //for each stock update the historical data
   for ($x=0; $x<=9; $x++) {
        $connection->prepare($query->get_last_date());
        $date=$connection->Dataset();
        //check if there are historical data
        if(!($date[$x]['recentDate']))
            $startdate="2016-Jan-01";
        else
            $startdate=$date[$x]['recentDate'];//since there are historical data the start date becomes the recent date
        $getHistory->getHistory($stocksymbol[$x],$startdate,date('Y/M/d'));//get historical data by a csv which is download from an URL
        $getHistory->updateHistory($stocksymbol[$x]);// update historical data
        $vr -> predict_VR($stocksymbol[$x]);
        $KDJ -> predict_KDJ($stocksymbol[$x]);
        $MACD -> predict_MACD($stocksymbol[$x]);
    }
   


    echo "Success.";
    $connection->disconnect();

?>



