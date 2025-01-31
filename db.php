<?
class db {
	
	//Parameters for connecting to DB(defined in individual db_local classes)
	function db($user='',$pass='',$database=''){
		$this->host = 'localhost';
		$this->user = $user;
		$this->password = $pass;
		$this->database = $database;	

		//Connect and select the database
		mysqli_connect($this->host,$this->user,$this->password);
		mysqli_select_db($this->database);
	}
	
	function select($database){
		 mysql_select_db($database);

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
		
		//echo "ST is $st \n<br>";
		//If the table already exists don't run the query
		if($this->table_exists($table)){
			echo "Sorry, table $table already exists \n<br>";
		}else{
			mysql_query($st)
				or die (mysql_error());
			echo "$table created! \n<br>";
		}
	}
	
	function table_exists($table) {
		$result = mysql_list_tables($this->database);
		$i = 0;
		while($i < mysql_num_rows($result)){
			$t = mysql_tablename($result,$i);
			if($t == $table){
				return 1;
			}
			$i++;
		}
		return 0;
	}
	function generate_bps(){
		if($this->table_exists('dbs')){
			echo "Sorry,bps already exists \n<br>";
			return false;
		}else{
			$q = 'create table bps(id int not null primary key default 0 auto_increment, name text)';
			if(!mysql_query($q)){
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
				mysql_query($q)
					or die("could not populate bps " . mysql_error());
			}
		}
	}
	function value_exists($table,$field,$value){
		$q = "select $field from $table";
		$qh = mysql_query($q);
		if($qh){
			while($row = mysql_fetch_array($qh)){
				if($row[name] == $value){
					return true;
				}
			}
			return false;
		}
		return false;
	}
}
?>
