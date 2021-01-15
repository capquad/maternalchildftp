<?php

class Database
{
	private $dbhost;
	private $dbpass;
	private $dbuser;
	private $query_result = null;
	private $lastError = null;

	public function __construct($dbhost, $dbuser, $dbpass)
	{
		$this->dbhost = $dbhost;
		$this->dbpass = $dbpass;
		$this->dbuser = $dbuser;
	}

	public function connect($dbname)
	{
		if (@!$this->connection) {
			$this->connection = @mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass);
			if ($this->connection) {
				$seldb = @mysqli_select_db($this->connection, $dbname);
				if ($seldb) {
					return true;
				}
				return false;
			}
			@mysqli_close($this->connection);
			return false;
		}
		return false;
	}

	public function disconnect()
	{
		if (@mysqli_close(@$this->connection)) {
			$this->connection = null;
			return true;
		}
		return false;
	}

	private function tableExists($table)
	{
		if (@$this->connection) {
			$sql = "SHOW TABLES LIKE '$table'";
			$query = @mysqli_query($this->connection, $sql);
			if ($query) {
				if (mysqli_num_rows($query) > 0) {
					return true;
				}
				return false;
			}
			return false;
		}
		return false;
	}

	public function select($table, $rows = "*", $where = null, $order = null, $limit = 0)
	{
		if ($this->tableExists($table)) {
			if (!is_string($rows)) {
				return false;
			}
			$sql = "SELECT $rows FROM $table";
			if ($where !== null) {
				$sql .= " WHERE $where";
			}
			if ($order !== null) {
				$sql .= " ORDER BY $order";
			}
			if ($limit > 0) {
				$sql .= " LIMIT $limit";
			}
			$query = @mysqli_query($this->connection, $sql);
			$result = [];
			if ($query) {
				if (mysqli_num_rows($query) < 1) {
					$this->query_result = [];
					return true;
				}
				if (mysqli_num_rows($query) === 1) {
					$result = [@mysqli_fetch_assoc($query)];
					// $result = [$result
				}
				if (mysqli_num_rows($query) > 1) {
					while ($rows = mysqli_fetch_assoc($query)) {
						$result[] = $rows;
					}
				}
				$this->query_result = $result;
				unset($result);
				return true;
			}
			$this->lastError = @mysqli_error($this->connection);
			return false;
		}
		$this->lastError = "Table '$table' does not exist.";
		return false;
	}

	public function insert($table, $row_val)
	{
		if ($this->tableExists($table)) {
			$inserts = $this->constructQuery($row_val, "insert");
			$rows = $inserts[0];
			$values = $inserts[1];
			$sql = "INSERT INTO $table ($rows) VALUES ($values);";
			$query = @mysqli_query($this->connection, $sql);
			if ($query) {
				return true;
			}
			$this->lastError = @mysqli_error($this->connection);
			return false;
		}
		$this->lastError = "Table '$table' does not exist.";
		return false;
	}

	public function multi_insert($data)
	{
		$sql = "";
		foreach ($data as $table => $row_val) {
			if ($this->tableExists($table)) {
				if (count($row_val) > 0) {
					$construct = "INSERT INTO $table ";
					$inserts = $this->constructQuery($row_val, "insert");
					$construct .= "(" . $inserts[0] . ") VALUES (" . $inserts[1] . ")";
					$sql .= $construct . "; ";
				} else {
					$this->lastError = "Table '$table' has no data attached to it.";
					return false;
				}
			} else {
				$this->lastError = "Table '$table' does not exist.";
				return false;
			}
		}
		$query = @mysqli_multi_query($this->connection, $sql);
		// Flush multi query so insert can be used after
		if ($query) {
			while (@mysqli_next_result($this->connection)) {
				if (!mysqli_more_results($this->connection)) break;
			}
			return true;
		}
		$this->lastError = mysqli_error($this->connection);
		return false;
	}

	public function update($table, $data, $where)
	{
		// Update records in a table with data and well-constructed WHERE clause
		if ($this->tableExists($table)) {
			if (!$this->testWhere($where)) {
				return false;
			}
			$sql = "UPDATE $table SET " . $this->constructQuery($data, "update") . " WHERE $where;";
			$query = @mysqli_query($this->connection, $sql);
			if ($query) {
				return true;
			}
			$this->lastError = @mysqli_error($this->connection);
			return false;
		}
		$this->lastError = "Table '$table' does not exist.";
		return false;
	}

	// Delete a record with well constructed WHERE clause
	public function delete($table, $condition)
	{
		if ($this->tableExists($table)) {
			if (!$this->testWhere($condition)) {
				return false;
			}
			$sql = "DELETE FROM $table WHERE $condition;";
			// echo $sql; return;
			$query = @mysqli_query($this->connection, $sql);
			if ($query) {
				return true;
			} else {
				$this->lastError = mysqli_error($this->connection);
				return false;
			}
		}
	}

	public function getResults()
	{
		// Extract and last result from Database Object
		$result = $this->query_result;
		return $result;
	}

	public function getError()
	{
		if ($this->lastError) {
			return $this->lastError;
		}
	}

	private function constructQuery($data, $mode)
	{
		// construct insert queries from row value arrays
		// Using modes such as 'insert', 'update'
		if ($mode === "insert") {
			$rows = implode(', ', array_keys($data));
			$value_list = array_values($data);
			$values = "";
			for ($i = 0; $i < count($value_list); $i += 1) {
				$values .= "'" . $value_list[$i] . "'";
				if ($i < count($value_list) - 1) {
					$values .= ", ";
				}
			}
			return [$rows, $values];
		}

		if ($mode === "update") {
			$rows = array_keys($data);
			$values = array_values($data);
			$set = "";
			for ($i = 0; $i < count($rows); $i += 1) {
				$set .= $rows[$i] . "='" . $values[$i] . "'";
				if ($i < count($rows) - 1) {
					$set .= ", ";
				}
			}
			return $set;
		}
	}

	private function testWhere($condition)
	{
		if (preg_match("/=/", $condition)) {
			return true;
		}
		$this->lastError = "<strong>Invalid WHERE clause</strong>: '$condition'";
		return false;
	}
}
