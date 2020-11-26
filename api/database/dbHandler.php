<?php

class DbHandler {

    private $conn;
	

    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
		if (!is_resource($this->conn)) 
			$this->conn = $db->connect();
    }
	
	public function disconnect() {
        mysqli_close($this->conn);
    }
	
    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        //return $result = $r->fetch_assoc();    
        $result = $r->fetch_assoc(); 
        $r->close();
        return $result;
    }

    

    function free_result($conn) {
        while (mysqli_more_results($conn) && mysqli_next_result($conn)) {

            $dummyResult = mysqli_use_result($conn);

            if ($dummyResult instanceof mysqli_result) {
                mysqli_free_result($conn);
            }
        }
    }

    
	
	public function getRecords($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
		//$r = $this->conn->query($query) or die("erro");
		$data = array();
             if($r->num_rows > 0){
                 
                 while($row = $r->fetch_assoc()){
                     $data[] = $row;
                 }
			 }

        $r->close();

		return $data;
        //return $result = $r->fetch_assoc();    
    }
    /**
     * Creating new record
     */
    
	
	public function executeSQL($query) {
        
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            
            return 1;
            } else {
            return NULL;
        }
    }
	
	public function executeInsert($query) {
        
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
            } else {
            return NULL;
        }
    }
	

	 
 
}



?>
