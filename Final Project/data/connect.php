<!-- Written by Jingxuan Chen
     Debugged by Xiaoyi Tang
     Assisted by Feng Rong -->
<?php

	class dbConnect {
		private $connect; //connect with database
		private $statement; //query prepared statement
		
		
		private $host = "localhost";
		private $user = "root";
		//private $password = "a2036808";
		private $db = "StockDatabase";
				
		//connect with database
		public function connect() 
		{
			$this->connect = new PDO('mysql:host='.$this->host.';dbname='.$this->db.';charset=utf8', $this->user);
			$this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}
		
		//prepare a satement
		public function prepare($query) 
		{
			$this->statement = $this->connect->prepare($query);
		}
		
		//bind value.
		public function bindvalue($parameter, $value, $type = null) 
		{
			if (is_null($type)) 
			{
				switch (true) 
				{
					case is_int($value):
						$type = PDO::PARAM_INT; //integer
						break;
					case is_bool($value):
						$type = PDO::PARAM_BOOL; //bool
						break;
					case is_null($value):
						$type = PDO::PARAM_NULL; //null
						break;
					default:
						$type = PDO::PARAM_STR; //string
				}
			}
			//bind statement
			$this->statement->bindValue($parameter, $value, $type);
		}
		
		//execute SQLquery
		public function execute()
		{
				try {
				return $this->statement->execute();
			}
			catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); //user friendly error message
			}

		}
		
		//get data
		public function Dataset()
		{
			$this->execute();
			return $this->statement->fetchAll(PDO::FETCH_ASSOC);
		}
		
		//disconnect from database
		public function disconnect()
		{
			$this->connect = null;
		}
	}
	?>