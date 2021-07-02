<?php

/* skel.class.php - base skeleton class
   EVERY class of your application should extend it
   so you should put all your commonly used code here */
   
class Skel {
    var $datalink;
    var $err_msg;
    var $table;
    var $pkey;
    var $fields = array();
    
    /**
     * @return boolean
     * @param int $id = null
     * @desc Constructor. Initializes the Database connection.
    */
    function Skel() {
        global $datalink;
        $this->datalink = $datalink;
        $this->init();
    }
    
    /**
     * @return void
     * @desc Skeleton initialization function.
    */
    /* This is just a skeleton to make sure the app doesn't crash
           but all your child classes should override it */
    function init() { ; }
        
    
    /**
     * @return boolean
     * @param int $id = null
     * @desc Filters the input received by POST and calls __commit if it is appropriate
    */
    function check_input($id = null) {
	# if $id != null
	# pop($id)
	# foreach($fields)
	# if o->$id != $_POST> $log." field changed, old: new"
        # Iterate through the object's fields
	if ($id != null) {
		$this->pop($id);
	}
	$o_change_log = "";
        foreach($this->fields as $field) {
            # Make sure they are all filled
            if (!isset($_POST[$field])) {
                # Otherwise, report every error
                $this->error_messages .= "Der Wert für Feld <b>" .
                $field . "</b> fehlt.<br>";
            } else {
		if ($id != null) {
			if ($this->$field != $_POST[$field]) {
				$o_change_log .= $field." alt: ".$this->$field." neu: ".$_POST[$field].",";
			}
		}
                $this->$field = $_POST[$field];
            }
        }
        
        # If errors have been reported, return FALSE
        if (!empty($this->error_messages)) {
            return FALSE;
        } else {
	    $result = $this->__commit($id);
		if ($id != null) {
			$mieter_name = get_mieter_name($this->table, $id);
			log_action($mieter_name.": ".ucfirst($this->table)." geändert(id: ".$id."): ".$o_change_log, $this->table, $id);
		} else {
			$mieter_name = get_mieter_name($this->table, $this->id);
			log_action($mieter_name.": ".ucfirst($this->table)." erstellt(id: ".$this->id."): ".$o_change_log, $this->table, $this->id);
		}
            # Otherwise, return whatever __commit returns
            return $result;
        }
    }
    function submit_input( $data, $id = null )
    {
      foreach ( $this->fields as $field ) {
        if (!isset($data[$field])) {
          $this->error_messages .= "Der Wert für Feld <b>" .
            $field . "</b> fehlt.<br>";
        } else {
          $this->$field = $data[$field];
        }
      }
      if (!empty($this->error_messages)) {
        return FALSE;
      } else {
        return $this->__commit($id);
      }
    }
    
    /**
     * @return string
     * @desc Returns a comma-separated list of fields
    */
    function __get_fields() {
        # Initialize an empty string
        $sequence = "";
        # Add a list of fields, comma separated
        foreach($this->fields as $field) {
            $sequence .= $field . ", ";
        }
        # then truncate the result and return
        $size = strlen($sequence)-2;
        return substr($sequence, 0, $size);
    }
    function __get_question_marks() {
      $sequence = "";
      foreach( $this->fields as $field ) {
        $sequence .= "?, ";
      }
      $size = strlen($sequence)-2;
      return substr($sequence, 0, $size);
    }
    
    /**
     * @return string
     * @desc Returns a comma-separated list of values
    */
    function __get_values() {
        $values = array();
        foreach( $this->fields as $field ) {
          array_push( $values, $this->$field );
        }
        return $values;
    }
    
    function __get_update_fields() {
        $sequence = "";
        foreach($this->fields as $field) {
            $sequence .= $field . " = ?, ";
        }
        $size = strlen($sequence)-2;
        return substr($sequence, 0, $size);
    }
    
    /**
     * @return boolean
     * @param int $id
     * @desc Fills out instance variables from database
    */
    function __commit($id = null) {
        # If we're not working on an existing record
        if (empty($id)) {
            # Create a new one
            return $this->__insert();
        } else {
            # Otherwise, update our current record.
            return $this->__update($id);
        }
    }
    
    


  /**
  * @return boolean
  * @param int $id
  * @desc Fills out instance variables from database
  */
  function pop($id) {
    $id = mysql_escape_string( $id );
    $query = "SELECT * FROM `" . $this->table . "` WHERE " . $this->pkey . " = '$id'";
    if ($this->datalink->isError($result = $this->datalink->getRow($query, DB_FETCHMODE_ASSOC))) {
      $this->error_messages = "SQL Fatal Error: " . DB::ErrorMessage($result);
      $this->error_messages .= "<br>SQL Query: <pre>\n$query\n</pre>\n<br>";
      return FALSE;
    } else {
      foreach(array_keys($result) as $current) {
        $this->$current = $result[$current];
      }
      return TRUE;
    }
  }

  /**
  * @return boolean
  * @desc Inserts values stored in current object in the database
  */
  function __insert() {
    $query = "INSERT INTO `".$this->table."` ( ".$this->__get_fields()." ) VALUES ( ".$this->__get_question_marks()." )";
    
    $data = $this->__get_values();

    if ($this->datalink->isError( $result = $this->datalink->query( $query, $data ) ) ) {
      $this->error_messages = "SQL Fatal Error: " . DB::ErrorMessage($result);
      $this->error_messages .= "<br>SQL Query: <pre>\n$query\n</pre>\n<br>";
      return FALSE;
    } else {
      $this->id = mysql_insert_id($this->datalink->connection);
      return TRUE;
    }
  }

  /**
  * @return boolean
  * @param int $id
  * @desc Updates appropriate record in the database.
  */
  function __update($id) {
    $query = "UPDATE `".$this->table."` SET ".$this->__get_update_fields()." WHERE id = ?";
    $data = $this->__get_values();
    array_push( $data, $id );
    
    if ($this->datalink->isError($result = $this->datalink->query($query, $data))) {
      $this->error_messages = "SQL Fatal Error: " . DB::ErrorMessage($result);
      $this->error_messages .= "<br>SQL Query: <pre>\n$query\n</pre>\n<br>";
      return FALSE;
    } else {
      return TRUE;
    }

  }
    
  /**
  * @return boolean
  * @param int $id
  * @desc Deletes an entry in the database.
  */
  function delete($id) {
    $query = "DELETE FROM `".$this->table."` WHERE `id` = ?";
    $data = array( $id );
    if ($this->datalink->isError($result = $this->datalink->query( $query, $data ) ) ) {
      $this->error_messages = "SQL Fatal Error: " . DB::ErrorMessage($result);
      $this->error_messages .= "<br>SQL Query: <pre>\n$query\n</pre>\n<br>";
      return FALSE;
    } else {
      return TRUE;
    }
  }


}


?>
