<?php

class Objekt extends Skel {
  var $id;
  var $name;
  var $adresse;

  var $error_messages;

  /**
  * @return void
  * @desc Constructor for classes that extend Skel
  */
  function init() {
    $this->table = 'objekte';
    $this->pkey = 'id';
    $this->fields = array( 'name', 'adresse' );
  }

    
}
?>
