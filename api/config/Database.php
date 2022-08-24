<?php
class Database{
	
	private $host  = 'localhost';
    private $user  = 'apablo';
    private $password   = "55891200";
    private $database  = "sistema_nomina"; 
    
    public function getConnection(){		
		$conn = new mysqli($this->host, $this->user, $this->password, $this->database);
		if($conn->connect_error){
			die("Error failed to connect to MySQL: " . $conn->connect_error);
		} else {
			return $conn;
		}
    }
}
?>