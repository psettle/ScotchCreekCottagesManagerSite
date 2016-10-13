<?php
include 'DBQuery.php';

/**
 * Base class for instances from tables
 * 
 * All variables that exist as column names should end with _p
 * @author Patrick
 *
 */
abstract class DBTableInstance {
	/**
	 * The name of the table this instance belongs to
	 * @var string
	 */
	protected $tableName;
	
	/**
	 * Initializes a instance from a table
	 * @param string $type
	 * 		'new' => creates an empty instance
	 * 		'standard' => loads an instance using $data
	 * @param array $data
	 * 		an associative array of 
	 * 		<column_name> => <column_value>, when loading using  method 'standard'
	 * 		these values will be used in a select query to load the first matching row
	 * 		(if there are multiple matching rows, no promises are made about which one is loaded)
	 */
	public function __construct($type = 'new', $data = array()) {
		if($type == 'new') {
			return;
		} else if($type == 'standard') {
			$this->setCopy($data);
			$this->getFromDB();
		}
	}
	
	
	/**
	 * Loads all matching instances that match $data and returns them
	 * in a 0 indexed array
	 * 
	 * Note: This function should use minimal db calls,
	 * at most 1 per table, except under really weird circumstances
	 * 
	 * @param array $data
	 * 		an associative array of 
	 * 		<column_name> => <column_value>
	 * 		all rows that match all of the provided names will be loaded
	 * 		note that calling with the default argument will load all rows
	 */
	public static function instanceLoadMultiple($data = array()) {
		//note to whomever is implementing this,
		//you can get the runtime class of an object with get_class($this)
		//and create a new one as $class = get_class($this); $new = new $class();
	}
	
	/**
	 * Checks all persistent (_p) variables to see if a row in the table exists
	 * with that name
	 * 
	 * @return bool
	 * 		true if the instance exists, false otherwise
	 */
	public function existsInDB() {
		return $this->getFromDB();
	}
	
	/**
	 * Creates the current instance as a new row in the database,
	 * @throws Exception
	 * 		If the instance already exists in the database
	 */
	public function storeToDB() {
		
	}
	
	/**
	 * Updates the instance in the database
	 * using the values in the object to find an instance
	 * and the values in $toUpdate as replacements,
	 * 
	 * Replaces member variables with updated values on success
	 * 
	 * @param array $toUpdate
	 */
	public function updateInDB($toUpdate = array()) {
		
	}
	
	/**
	 * Attempts to load an instance from the database
	 * using the currently set members, loads the values into this object
	 * 
	 * @return bool
	 * 		true on success, false on failure
	 */
	public function getFromDB() {
		
	}
	
	
	/**
	 * Setter for all member variables
	 * @param string $key
	 * 		The member to access
	 * @param mixed $value
	 * 		The value to set there
	 */
	public function setProperty($key, $value) {
		if(property_exists($this, $key)) {
			$this->$key = $value;
			return;
		}
		
		$keyP = $key . '_p';
		//add _p to $key and try again
		if(property_exists($this, $keyP)) {
			$this->$keyP = $value;
			return;
		}
		
		//if the key didn't exist, throw an exception
		throw new DBException("$key doesn't exist in the object");
	}
	
	/**
	 * Getter for all member variables
	 * @param string $key
	 * 		The member to access
	 * @return mixed
	 * 		The value of said member
	 */
	public function getProperty($key) {
		if(property_exists($this, $key)) {
			return $this->$key;
		}
		
		$keyP = $key . '_p';
		//add _p to $key and try again	
		if(property_exists($this, $keyP)) {
			return $this->$keyP;
		}
		
		//otherwise throw an exception
		throw new DBException("$key doesn't exist in the object.");
	}
	
	/**
	 * Creates an associative array of all member variables
	 * @return array
	 */
	public function toArray() {
		return get_object_vars($this);
	}
	
	/**
	 * Sets this object to a copy of an associative array
	 * or another object
	 * @param array | object $toCopy
	 * 		The object to copy
	 * 		Either another instance of this class,
	 * 		or an associative array of $member => $value
	 */
	public function setCopy($toCopy) {
		//convert to array if required
		if(is_object($toCopy)) {
			$toCopy = $toCopy->toArray();
		}
		
		//iterate over the things to copy
		foreach($toCopy as $key => $value) {
			try {
				$this->setProperty($key, $value);
			} catch (DBException $e) {
				//key didn't exist or wasn't a valid variable name
				//just continue with the next one
			}
		}
	}
	
	
	
}