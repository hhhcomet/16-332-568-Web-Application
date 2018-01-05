<!-- written by Xiaoyi Tang
     debugged by Feng Rong 
     assisted by Rong Zhang -->
<?php


    include_once "query.php";
    include_once "connect.php";
    
    class GetHistory {
        private $connect;//dBconnect object
        private $query;//query object
        private $historyURL="http://www.google.com/finance/historical?q=";
        private $CSV;
        //construct connect and query object
        public function __construct() {
	        $this->connect = new dbConnect();
	        $this->query = new query();
	    }
	    //get file from given url
	    public function getFile($URL) {
	        $ch=curl_init();
	        
            curl_setopt($ch,CURLOPT_URL,$URL);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $data=curl_exec($ch);
            curl_close($ch);
            return $data;
	    }
	    //get historical file csv
	   public function getHistory($symbol, $start, $end){

	    $startdate=explode("-",$start);
	    $enddate=explode("/",$end);
	    $URL=$this->historyURL.$symbol."&startdate=".($startdate[1])."+".$startdate[2]."%2C+".$startdate[0]."&enddate=".($enddate[1])."+".$enddate[2]."%2C+".$enddate[0]."&output=csv";
	    $this->CSV=$this->getFile($URL);
	}
	//update the historical data to sql
	    public function updateHistory($Symbol)
	{
		$fileline = str_getcsv($this->CSV,"\n");// change csv to array
		$this->connect->connect();
		$first = true;
		$this->connect->prepare($this->query->insert_historical());
		foreach($fileline as $line)//for each line;
		{
			$content = str_getcsv($line);
			//skip the first line
			if($first)
			{
				$first=false;
				continue;
			}
			//copy data to sql
			$this->connect->bindvalue(1,$Symbol);
			$content[0]=strtotime($content[0]);
			$content[0]=date('Y/m/d',$content[0]);
			$this->connect->bindvalue(2,$content[0]);
			$this->connect->bindvalue(3,$content[1]);
			$this->connect->bindvalue(4,$content[2]);
			$this->connect->bindvalue(5,$content[3]);
			$this->connect->bindvalue(6,$content[4]);
			$this->connect->bindvalue(7,$content[5]);
			$this->connect->bindvalue(8,$content[4]);
			$this->connect->execute();
		}
		$this->connect->disconnect();
	}
    }

    ?>