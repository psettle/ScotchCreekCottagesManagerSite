<?php
include 'DBException.php';


/**
 * Executes a query to the set DB, returns
 * a result, either a matches set or success, failure
 * depending on the nature of the query
 * @param string $query
 * 		The SQL query to execute, arguments to the query
 * 		should be parameterized to prevent SQL injection attacks
 * @param array $args
 * 		Supplimental arguments to the query, to prevent injection
 * @return mixed
 * 		The result of the query
 */
function query($query, $args = array()) {
	$DBQuery = new DBQuery();
	
	$DBQuery->setArguments($args);
	
	$DBQuery->setQuery($query);
	
	return $DBQuery->execute();
}

/**
 * Helper class to execute query to a mysql database
 * @author Patrick
 *
 */
class DBQuery {
	
	/**
	 * The connection to the database
	 * 
	 * @var mysqli
	 */
	protected $connection;
	
	/**
	 * The query to excecute
	 * @var string
	 */
	protected $query;
	
	
	/**
	 * A list of parameters to the query
	 * 
	 * @var mixed[]
	 */
	protected $arguments;
	
	/**
	 * The name of the save file for database credentials
	 * @var string
	 */
	protected static $dbConnectionFile = "db_connection.bin";
	
	/**
	 * Saves the database credentials to a file
	 * @param string $host
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 */
	public static function saveConnectionCredentials($host, $username, $password, $database) {
		$data = array (
			'host' => $host,
			'user' => $username,
			'password' => $password,
			'database' => $database,
		);
		
		file_put_contents(DBQuery::$dbConnectionFile, serialize($data));
	}
	
	/**
	 * Initializes a connection with the DB to prepare for a query
	 */
	public function __construct() {
		
		$connectionData = unserialize(file_get_contents(DBQuery::$dbConnectionFile));
		
		//TODO: initializes a connection with the database
		$this->connection = new mysqli($connectionData['host'], $connectionData['user'], $connectionData['password'], $connectionData['database']);
		
		if($this->connection->connect_error) {
			throw new DBException("{$this->connection->connect_error}");
		}
	}
	
	/**
	 * Sets the query for this object
	 * @param string $query
	 * 		The query to excecute
	 */
	public function setQuery($query) {
		$this->query = $query;
	}
	
	/**
	 * Adds any supplimental arguments to the object
	 * @param array $args
 	 * 		Supplimental arguments to the query, to prevent injection
	 */
	public function setArguments($args) {
		$this->arguments = $args;
	}
	
	
	/**
	 * Runs the query to the database
	 */
	public function execute() {
		//prepare the statement
		$statement = $this->connection->prepare($this->query);
		
		if(!$statement) {
			throw new DBException("{$this->connection->error}");
		}
		
		if(count($this->arguments)) {
			//bind the arguments
			$types = '';
			foreach($this->arguments as $argument) {
				$type = gettype($argument);
				
				if($type == 'integer') {
					$type = 'i';
				} else if($type == 'double') {
					$type = 'd';
				} else if($type == 'string') {
					$type = 's';
				} else {
					$type = 'b'; //b is for blob, i.e. binary data / serialization
				}
				
				$types .= $type;	
			}
			//the ... unpacks the arguments into a list of vargs
			$success = $statement->bind_param($types, ...$this->arguments);	
			
			if(!$success) {
				throw new DBException("{$this->connection->error}");
			}
		}
		
		
		
		//execute the statement
		$success = $statement->execute();
		
		
		if(!$success) {
			throw new DBException("{$this->connection->error}");
		}
		
		//get the results
		$result = $statement->get_result();
		
		
		//extract results from the result
		$results = array();
		
		foreach($result as $row) {
			$results[] = $row;
		}
		
		$statement->close();
		
		return $results;
	}
	
	
	/**
	 * Safely closes the connection to the database
	 */
	public function __destruct() {
		$this->connection->close();
		$this->connection = null;
	}
}











