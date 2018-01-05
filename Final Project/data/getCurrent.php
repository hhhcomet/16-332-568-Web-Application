<?php
//written by JINGXUAN CHEN
//assisted by Xiaoyi Tang
//debugged by Zhe Chang

    include_once "query.php";
    include_once "connect.php";
    date_default_timezone_set('America/New_york');

    class GetCurrent {
        private $connect;//dBconnect object
        private $query;//query object
        private $currentURL="http://download.finance.yahoo.com/d/quotes.csv?s=";
        private $CSV;
        //construct connect and query object
        public function __construct() {
	        $this->connect = new dbConnect();
	        $this->query = new query();
	    }
	    //get file from URL
	    public function getFile($URL) {
	        $ch=curl_init();
	        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_URL,$URL);
            $data=curl_exec($ch);
            curl_close($ch);
            return $data;
	    }
	    //get current file for given symbol which is download from a URL
	    public function getCurrentFile($symbol){
	        $URL = $this->currentURL.$symbol."&f=l1v";
	        $this->CSV = $this->getFile($URL);
	    }
	    // update current data for given symbol
	    public function updateCurrent($symbol){
	        $content=str_getcsv($this->CSV);// change csv to an array
            $this->connect->connect();
 		    $this->connect->prepare($this->query->insert_real_time());
 		    //copy data from csv to sql
		    $this->connect->bindvalue(1,$symbol);
		    $this->connect->bindvalue(2,date("Y/m/d",time()));//date
		    $this->connect->bindvalue(3,date("H:i:s",time()));//time
		    $this->connect->bindvalue(4,$content[0]);
		    $this->connect->bindvalue(5,$content[1]);
		    $this->connect->execute();
		    $this->connect->disconnect();
	    }

    }

    ?>