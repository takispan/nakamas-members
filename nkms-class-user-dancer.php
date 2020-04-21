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
  // no constructor so WP_User's constructor is users

}

class DanceSchool extends WP_User {

  $ds_dancers_list_array = [];

  $dance_groups = [];

  $da

}

class DanceGroup {

  private $group_name;

  private $size;

  private $main_dancers;

  //$sub_dancers;

  function __construct($group_name, $size) {
    this->group_name = $group_name;
    this->$size = $size;
    this->$main_dancers = [];
  }
  public function getSize() {
    return sizeof($main_dancers);
  }

  public function getDancers() {
    return $main_dancers;
  }

  public function addDancer($id) {
		if (!in_array($id, $main_dancers)) {
			array_push($id, $main_dancers);
      return true;
		}
    return false;
  }

  public function removeDancer($id) {
    if (!in_array($id, $main_dancers)) {
        return false;
    }
    $main_dancers = array_diff($main_dancers, [$id]);
    return true;
  }
}
?>
