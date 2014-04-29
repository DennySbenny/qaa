<?php

    include (__DIR__ . '/../exceptions/DatabaseException.php');


	class Mysql {
        
		private $link = null;
        
		private $login    = null;
		private $password = null;
        private $database = null;
		private $hostname = null;

		public function __construct($login, $password, $database, $hostname) {
            
			$this->throwExceptionIfNotSet('login', $login);
			$this->throwExceptionIfNotSet('database', $database);
			$this->throwExceptionIfNotSet('hostname', $hostname);
            
			$this->login    = $login;
			$this->password = $password;
			$this->database = $database;
			$this->hostname = $hostname;

			$this->connect();
			//$this->selectDatabase();
		}
        
        function __destruct() {
            //$this->close();
        }

		private function throwExceptionIfNotSet($argName, $argValue) {
			if (empty($argValue)) {
				throw new DatabaseException("'${argName}' not set");
			}
		}

		public function connect() {
			$this->link = mysqli_connect($this->hostname, $this->login, $this->password, $this->database);
			if (!$this->link) {
				throw new DatabaseException(
					sprintf('Cannot connect to database. mysql_connect() to ' . $this->hostname . ' with login ' . $this->login . ' and database ' . $this->database . ' fails. MySQL Error: '. mysql_error())
				);
			}
		}

        
		public function selectDatabase() {
			$ret = mysql_select_db($this->database);
			if (!$ret) {
				throw new DatabaseException("Cannot select database {$this->database} - MySQL Error: " . mysql_error());
			}
		}
        
        
		public function close () {
            if (!is_null($this->link)) 
                return $result = $this->link->close() or die($this->link->error());
            return false;
		}
		
		
		public function doQuery($query) {
            /* echo "<br />" . $query . "<br />"; */
            try {
                $result = $this->link->query($query) or die(mysqli_error($this->link));
                $this->link->commit();
            } catch (DatabaseException $e) {
               $this->link->rollback();
               throw new DatabaseException("Error in execution query: " . $e->getMessage() . " - Query: " . $query);
            }
			return $result;
		}
        
        
        
        
        
        public function transformSqlResult($result, $mode) {
            if (strtoupper($mode) == 'ARRAY') return mysql_fetch_array($result);
            if (strtoupper($mode) == 'JSON') return json_encode(mysql_fetch_array($result));
            return $result;
        }
        
        
        
		public function autoGetter($table_name, $field_name, $clausole_name, $clausole_value) {
            $query = "SELECT " . $field_name . " FROM " . $table_name . " WHERE " . $clausole_name . " = " . $clausole_value;
            try {
                $result = $this->doQuery($query);
            } catch (DatabaseException $e) {
                throw new DatabaseException("Error in execution query: " . $e->getMessage() . " - Query: " . $query . "\n");
            }
            if ($result && $result->num_rows) {  
                return $result->fetch_assoc()[$field_name];  
            }
            
            return null;
		}

        public function autoSetter($table_name, $field_name, $clausole_name, $clausole_value, $value) {
            $query = "UPDATE " . $table_name . " SET " . $field_name . " = " . $value . " WHERE " . $clausole_name . " = " . $clausole_value;
            
            try {
                $result = $this->doQuery($query);
            } catch (DatabaseException $e) {
                throw new DatabaseException("Error in execution query: " . $e->getMessage() . " - Query: " . $query . "\n");
            }
            return $result;
		}
        


	}


?>

