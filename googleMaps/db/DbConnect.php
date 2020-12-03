<?php 
	//db connection with PDO
	class DbConnect {
		private $host = 'localhost';
		private $user = 'root';
		private $pass = '';
		private $dbName = 'bfcmatest';

		public function connect() {
			try {
				$connection = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
				$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $connection;
			} catch( PDOException $e) {
				echo 'Database Error: ' . $e->getMessage();
			}
		}
	}
 ?>