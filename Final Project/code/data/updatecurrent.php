<?php
//written by RongZhang
//Debugged by Xiaoyi Tang
//Assisted by Xiuqi Ye
    include_once "getCurrent.php";
    ignore_user_abort();
    set_time_limit(0);
    $interval = 60000000;

    $stocksymbol=array("GOOG","YHOO","MSFT","GE","PTR","IBM","SNGX","ISRG","EGT","AAPL");
    $getCurrent = new GetCurrent();//get current object

    while(true)
    {
        foreach ($stocksymbol as $value) {
        $getCurrent->getCurrentFile($value);//get current data for each stock by csv which is dowmload form an URL
        $getCurrent->updateCurrent($value);//update current data to sql
        }
        usleep($interval);
        echo "success";

    }

    

    echo "success";

?>