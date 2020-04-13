<?php

/**
  * Dancer Object to represent a dancer in a dance /**
  * Each dancer has a list of attributes:
  * Unique User Identifier (UID)
  * First and Last name
  * A list of groups that the member is part of
  *
*/
class dancer extends WP_User {
  // no constructor so WP_User's constructor is used

  private $groups = "IT WORKS";

  // magic method to detect $user->my_data
  function __get( $key ) {
    // fallback to default WP_User magic method
    return parent::__get( $key );
  }

  function getGroups() {
    return $this->$groups;
  }
}
?>
