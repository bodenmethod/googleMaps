<?php 
	
	//create class and getters and settters
	class bfcmatest	{
		private $id;
		private $name;
		private $address;
		private $type;
		private $lat;
		private $lng;
		private $connection;
		private $tableName = "customers";

		function setId($id) { $this->id = $id; }
		function getId() { return $this->id; }
		function setName($name) { $this->name = $name; }
		function getName() { return $this->name; }
		function setAddress($address) { $this->address = $address; }
		function getAddress() { return $this->address; }
		function setType($type) { $this->type = $type; }
		function getType() { return $this->type; }
		function setLat($lat) { $this->lat = $lat; }
		function getLat() { return $this->lat; }
		function setLng($lng) { $this->lng = $lng; }
		function getLng() { return $this->lng; }

		//create connection object
		public function __construct() {
			require_once('db/DbConnect.php');
			$connection = new DbConnect;
			$this->connection = $connection->connect();
		}

		//collect data from customers with NULL lat and lng (null used when entering new customer into database as module will create the lat lng dynamically)
		public function getCustomersBlankLatLng() {
			$sql = "SELECT * FROM $this->tableName WHERE lat IS NULL AND lng IS NULL";
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		//collect data from all customers
		public function getAllCustomers() {
			$sql = "SELECT * FROM $this->tableName";
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		//collect data from search button
		public function getSearchedCustomer() {

			if(isset($_GET["SearchButton"])){
			$Search		= $_GET["Search"];
			$sql 		= "SELECT * FROM $this->tableName WHERE name=:searcH OR address=:searcH";
			$stmt		= $this->connection->prepare($sql);
			$stmt->bindValue(':searcH', $Search);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}

		//create sql to update the db with customer lat lng
		public function updateCustomersWithLatLng() {
			$sql = "UPDATE $this->tableName SET lat = :lat, lng = :lng WHERE id = :id";
			$stmt = $this->connection->prepare($sql);
			$stmt->bindParam(':lat', $this->lat);
			$stmt->bindParam(':lng', $this->lng);
			$stmt->bindParam(':id', $this->id);

			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
		}
	}

?>