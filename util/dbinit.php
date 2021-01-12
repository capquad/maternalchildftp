<?php
class Database
{
	// Database Object for all CRUD operations
	private $connection; // coonection variable to hold mysqli_connection data
	private $db_host = "localhost";
	private $db_user = "root";
	private $db_pass = "";
	private $db_name = "admin_mc";
	private $result = array(); //initialize empty results array

	public function getResults()
	// Public funtion to return the results of a query
	{
		$result = $this->result;
		$this->result = array(); // Empty Array
		return $result;
	}

	public function connect()
	{
		if (!@$this->con) { // $this->con represents connected state of the database and server
			$this->connection = @mysqli_connect($this->db_host, $this->db_user, $this->db_pass);
			$conn = $this->connection;
			if ($conn) {
				$seldb = @mysqli_select_db($conn, $this->db_name);
				if ($seldb) {
					$this->con = true;
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

	public function disconnect()
	{
		if ($this->con) {
			$discon = @mysqli_close($this->connection);
			if ($discon) {
				$this->con = false;
				return true;
			} else {
				return false;
			}
		}
	}

	private function tableExists($table)
	{
		$query = @mysqli_query($this->connection, 'SHOW TABLES FROM ' . $this->db_name . ' LIKE "' . $table . '"');
		if ($query) {
			if (mysqli_num_rows($query) == 1) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function select($table, $rows = '*', $where = null, $order = null)
	{
		// Compiling the query with supplied parameters
		$q = "SELECT $rows FROM $table ";
		if ($where != null) {
			$q .= "WHERE $where";
		}
		if ($order != null) {
			$q .= " ORDER BY $order";
		}
		if ($this->tableExists($table)) {

			$query = @mysqli_query($this->connection, $q); //Execute Query

			if ($query) {
				$this->numResults = mysqli_num_rows($query); //Get number of rows found

				for ($i = 0; $i < $this->numResults; $i++) {
					$r = mysqli_fetch_array($query); // Extract results into array 1;
					$key = array_keys($r); // Get keys of the extracted array

					for ($x = 0; $x < count($key); $x++) {
						// Sanitize keys? 
						if (!is_int($key[$x])) {
							if (mysqli_num_rows($query) > 1) {
								$this->result[$i][$key[$x]] = $r[$key[$x]]; // if num_rows > 1, convert into multidimensional array
							} else if (mysqli_num_rows($query) < 1) {
								$this->result = null; // for no rows; if num_rows < 1
								return false;
							} else {
								$this->result[$key[$x]] = $r[$key[$x]]; // for 1 row only; if num_rows == 1
							}
						}
					}
				}

				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function insert($table, $values, $rows = null)
	// $values = array of values
	{
		if ($this->tableExists($table)) {
			$insert = 'INSERT INTO ' . $table;
			if ($rows != null) {
				$insert .= ' (' . $rows . ')';
			}
			for ($i = 0; $i < count($values); $i++) {
				if (is_string($values[$i]))
					$values[$i] = '"' . $values[$i] . '"';
			}
			$values = implode(',', $values);
			$insert .= ' VALUES (' . $values . ')';
			// return $insert;
			$ins = @mysqli_query($this->connection, $insert);
			if ($ins) {
				return true;
			} else {
				$this->error = @mysqli_error($this->connection);
				return false;
			}
		}
	}

	public function delete($table, $where = null)
	{
		if ($this->tableExists($table)) {
			if ($where == null) {
				$delete = 'DELETE ' . $table;
			} else {
				$delete = 'DELETE FROM ' . $table . ' WHERE ' . $where;
			}

			$del = @mysqli_query($this->connection, $delete);
			if ($del) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function update($table, $rows, $where)
	{
		if ($this->tableExists($table)) {
			// Parse where values
			// even values (including 0) contain the where rows,
			// odd values contain the clauses for the rows
			for ($i = 0; $i < count($where); $i++) {
				if ($i % 2 != 0) {
					if (is_string($where[$i])) {
						if (($i + 1) != null)
							$where[$i] = '"' . $where[$i] . '" AND ';
						else
							$where[$i] = '"' . $where[$i] . '"';
					}
				}
			}

			$where = implode('=', $where);

			$update = 'UPDATE ' . $table . ' SET ';
			$keys = array_keys($rows);

			for ($i = 0; $i < count($rows); $i++) {
				if (is_string($rows[$keys[$i]])) {
					$update .= $keys[$i] . '="' . $rows[$keys[$i]] . '"';
				} else {
					$update .= $keys[$i] . '=' . $rows[$keys[$i]];
				}

				// Parse to add commas
				if ($i != count($rows) - 1) {
					$update .= ',';
				}
			}

			$update .= ' WHERE ' . $where;
			// echo $update;
			// exit();
			$query = @mysqli_query($this->connection, $update);
			if ($query) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function getConnData()
	{
		return $this->connection;
	}

	public function getDBInfo()
	{
		return [$this->db_host, $this->db_name, $this->db_user];
	}

	public function getError() {
		$this->error = @mysqli_error($this->connection);
		return $this->error;
	}
}
