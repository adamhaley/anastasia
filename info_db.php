<?

class info_db extends db {
	
	function info_db(){
		$this->host = 'localhost';
		
		//Connect and select the database
		$this->db('root','flam7ingo','info');
	}
}
?>
