<?
class db {
	//This class is used to connect to the database and generate tables from blueprint objects
	var $dbh;
	
	//Parameters for connecting to DB(defined in individual db_local classes)
	function db($user='root',$pass='password',$database='testsite'){

		$dir = 'sqlite:' . dirname(__FILE__) . '/testsite.sqlite';
		//echo $dir;
		//die;
		$this->dbh  = new PDO($dir);
	}
	
	function generate_from_blueprint($bp) {
		//This function generates a table from a blueprint object
		
		//Get the table name and field list from blueprint object
		$table = $bp->props[table];
		$fields = $bp->props[fields];
	
		//Build the Create Table Query based on blueprint fields list
		$st = "create table $table (";
	
		while(list($key,$value) = each($fields)){
			if(!preg_match("/^label/",$key)){
				$st .= "$key ";
				$type = $fields[$key][type];
				$type = $value[type];
				$length = '';
				$kobj = $bp->keywords[$key];
				if($type == 'keyword_list' || $type == 'keyword_select'){
						$kobj = $keywords[$key];
						eval("\$typeobj  = new $type(\$key,\$value,\$bp,\$kobj);");
				}else if(class_exists($type)){
						eval("\$typeobj = new $type(\$key,\$value,\$bp,\$http_vars,\$post_files);");

				}else{
						$typeobj = new type($key,$value,$bp,$http_vars);
				}
				$st .= $typeobj->database_field();

				$st .= ",";
			}
		}

		//Strip trailing comma
		$st = preg_replace("/,$/","",$st);
		$st .= ")";
		
		echo "$st \n<br>";
		//If the table already exists don't run the query

		try{
			$this->dbh->exec($st);
		}catch(PDOException $e){
			echo $e->getMessage();
		}
		echo "$table created! \n<br>";
	}
	
	function table_exists($table) {
		//This function checks to see if a table exists in the database
		$st = "show tables";
		while($row = $this->dbh->query($st)){
			if($row[0] == $table){
				return true;
			}
		}
		return false;
	}
	function generate_bps(){
		if($this->table_exists('bps')){
			echo "Sorry,bps already exists \n<br>";
			return false;
		}else{
			$q = 'create table bps(id int not null primary key default 0 auto_increment, name text)';
			if(!$this->dbh->exec($q)){
				echo "cound not create bps : " . mysql_error();
			}else{	
				echo "bps created!\n<br>";
			}
		}
	}
	function populate_bps($bparray){
                while(list($key,$value) = each($bparray)){
			if(!$this->value_exists('bps','name',$key)){
				$q = "insert into bps values(null,\"$key\")";
				$this->dbh->query($q)
					or die("could not populate bps ");
			}
		}
	}
	function value_exists($table,$field,$value){
		$q = "select $field from $table";
		while($row = $this->dbh->query($q)){
			if($row[name] == $value){
				return true;
			}
		}
		return false;
		return false;
	}
}
?>
