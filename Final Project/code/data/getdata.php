<!-- written by Zhe Chang
     debugged by Xiuqi YE
     assisted by Xiaoyi Tang -->
<?php

include_once"query.php ";//query
include_once" connect.php";// connection

class stockgetdata{
	private $dbcon;
	private $qu;
	public function copy()
	{
	this->dbcon = new dbConnect();
	this->qu = new query();
	}
	public function gethistorical($doc,$Symbol)
	{
		$fileline = str_getcsv($doc,"\n");
		this->dbcon->connect();
		$first = true;
		$this->dbcon->prepare($this->qu->insert_historical());
		foreach($fileline as $line)
		{
			$content = str_getcsv($line);
			if($first)
			{
				$first=false;
				continue;
			}
			$this->dbcon->bindvalue(1,$Symbol);
			$this->dbcon->bindvalue(2,$content[0]);
			$this->dbcon->bindvalue(3,$content[1]);
			$this->dbcon->bindvalue(4,$content[2]);
			$this->dbcon->bindvalue(5,$content[3]);
			$this->dbcon->bindvalue(6,$content[4]);
			$this->dbcon->bindvalue(7,$content[5]);
			$this->dbcon->bindvalue(8,$content[6]);
			$this->dbcon->execute();
		}
		$this->dbcon->disconnect();
	}
	public function getcurrentinfo($doc,$Symbol)
	{
		$content=str_getcsv(doc);
		$this->dbcon->connect();
		$this->dbcon->prepare($this->qu->insert_real_time());
		$this->dbcon->bindvalue(1,$Symbol);
		$this->dbcon->bindvalue(2,$content[0]);
		$this->dbcon->bindvalue(3,$content[1]);
		$this->dbcon->bindvalue(4,$content[2]);
		$this->dbcon->bindvalue(5,$content[3]);
		$this->dbcon->execute();
		$this->dbcon->disconnect;
	}
}
?>