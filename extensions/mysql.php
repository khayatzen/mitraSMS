<?php



require_once("access.conf.php");



class sql_db {

	var $db_connect_id;

	var $query_result;

	var $row = array();

	var $rowset = array();

	var $num_queries = 0;

	var $total_time_db = 0;

	var $time_query = "";

	

	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true) {

		$this->db_connect_id = ($persistency) ? @mysql_pconnect($sqlserver, $sqluser, $sqlpassword) : @mysql_connect($sqlserver, $sqluser, $sqlpassword);

		if ($this->db_connect_id) {

			if ($database != "" && !@mysql_select_db($database)) {

				@mysql_close($this->db_connect_id);

				$this->db_connect_id = false;

			}

			return $this->db_connect_id;

		} else {

			return false;

		}

	}



	function sql_close() {

		if ($this->db_connect_id) {

			if ($this->query_result) @mysql_free_result($this->query_result);

			$result = @mysql_close($this->db_connect_id);

			return $result;

		} else {

			return false;

		}

	}

        function split_sql($sql) {

	$sql = trim($sql);

	$sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);



	$buffer = array();

	$ret = array();

	$in_string = false;



	for($i=0; $i<strlen($sql)-1; $i++) {

		if($sql[$i] == ";" && !$in_string) {

			$ret[] = substr($sql, 0, $i);

			$sql = substr($sql, $i + 1);

			$i = 0;

		}



		if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {

			$in_string = false;

		}

		elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {

			$in_string = $sql[$i];

		}

		if(isset($buffer[1])) {

			$buffer[0] = $buffer[1];

		}

		$buffer[1] = $sql[$i];

	}



	if(!empty($sql)) {

		$ret[] = $sql;

	}

	return($ret);

}

	function sql_query($query = "", $transaction = false) {

            global $db_prefix;

		unset($this->query_result);

		if ($query != "") {

                        $pieces  = $this->split_sql($query);

                	for ($i=0; $i<count($pieces); $i++) {

                		$pieces[$i] = trim($pieces[$i]);

                		if(!empty($pieces[$i]) && $pieces[$i] != "#") {

                			$pieces[$i] = str_replace( "#__", $db_prefix, $pieces[$i]);

                			/*if (!$result = mysql_query ($pieces[$i])) {

                				$errors[] = array ( mysql_error(), $pieces[$i] );

                			}*/

                                        $this->query_result = @mysql_query($pieces[$i], $this->db_connect_id);

                		}

                	}

			

		}

		if ($this->query_result) {

			$this->num_queries += 1;

			unset($this->row[$this->query_result]);

			unset($this->rowset[$this->query_result]);

			return $this->query_result;

		} else {

			//return ($transaction == END_TRANSACTION) ? true : false;

		}

	}



	function sql_numrows($query_id = 0) {

		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			$result = @mysql_num_rows($query_id);

			return $result;

		} else {

			return false;

		}

	}



	function sql_affectedrows() {

		if ($this->db_connect_id) {

			$result = @mysql_affected_rows($this->db_connect_id);

			return $result;

		} else {

			return false;

		}

	}



	function sql_numfields($query_id = 0) {

		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			$result = @mysql_num_fields($query_id);

			return $result;

		} else {

			return false;

		}

	}



	function sql_fieldname($offset, $query_id = 0) {

		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			$result = @mysql_field_name($query_id, $offset);

			return $result;

		} else {

			return false;

		}

	}



	function sql_fieldtype($offset, $query_id = 0) {

		if (!$query_id) $query_id = $this->query_result;

		if($query_id) {

			$result = @mysql_field_type($query_id, $offset);

			return $result;

		} else {

			return false;

		}

	}



	function sql_fetchrow($query_id = 0) {

		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			$this->row[$query_id] = @mysql_fetch_array($query_id);

			return $this->row[$query_id];

		} else {

			return false;

		}

	}

        function sql_fetchassoc($query_id = 0) {

		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			$this->row[$query_id] = @mysql_fetch_assoc($query_id);

			return $this->row[$query_id];

		} else {

			return false;

		}

	}

	function sql_fetchrowset($query_id = 0) {



		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			unset($this->rowset[$query_id]);

			unset($this->row[$query_id]);

			while ($this->rowset[$query_id] = @mysql_fetch_array($query_id)) {

				$result[] = $this->rowset[$query_id];

			}

			return $result;

		} else {

			return false;

		}

	}



	function sql_fetchfield($field, $rownum = -1, $query_id = 0) {

		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			if ($rownum > -1) {

				$result = @mysql_result($query_id, $rownum, $field);

			} else {

				if (empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {

					if ($this->sql_fetchrow()) {

						$result = $this->row[$query_id][$field];

					}

				} else {

					if ($this->rowset[$query_id]) {

						$result = $this->rowset[$query_id][0][$field];

					} else if ($this->row[$query_id]) {

						$result = $this->row[$query_id][$field];

					}

				}

			}

			return $result;

		} else {

			return false;

		}

	}



	function sql_rowseek($rownum, $query_id = 0) {

		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			$result = @mysql_data_seek($query_id, $rownum);

			return $result;

		} else {

			return false;

		}

	}



	function sql_nextid() {

		if ($this->db_connect_id) {

			$result = @mysql_insert_id($this->db_connect_id);

			return $result;

		} else {

			return false;

		}

	}

          function sql_lastid() {

		if ($this->db_connect_id) {

			$result = @mysql_insert_id($this->db_connect_id);

			return $result;

		} else {

			return false;

		}

	}

	function sql_freeresult($query_id = 0){

		if (!$query_id) $query_id = $this->query_result;

		if ($query_id) {

			unset($this->row[$query_id]);

			unset($this->rowset[$query_id]);

			@mysql_free_result($query_id);

			return true;

		} else {

			return false;

		}

	}

          function recordExist($table_name,$key,$key_value){

                    $sql = "SELECT * FROM `$table_name` WHERE `$key`='$key_value'";

                    $query = @mysql_query($sql);

                    $rows = @mysql_num_rows($query);

                    if($rows>0){return true;}else{return false;}

          }

	function sql_error($query_id = 0) {

		$result["message"] = @mysql_error($this->db_connect_id);

		$result["code"] = @mysql_errno($this->db_connect_id);

		return $result;

	}

	/* function sql_real_escape_string($field){

                  return @mysql_real_escape_string($field));

          } */

	

}



$con = new sql_db($mysql_host, $mysql_user, $mysql_password, $mysql_database, false);

if (!$con->db_connect_id)
die("<h1>Ada masalah dengan koneksi Database</h1>");

?>