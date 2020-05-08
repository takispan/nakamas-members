<?php

/**
  * Dancer Object to represent a dancer in a dance /**
  * Each dancer has a list of attributes:
  * Unique User Identifier (UID)
  * First and Last name
  * A list of groups that the member is part of
  *
*/
class Dancer extends WP_User {
  // no constructor so WP_User's constructor is used

}

/**
  * DanceGroup Object to represent a dance group
  *
  * Attributes:
  * group_name   = Name
  * size         = Size (number of Dancers in Group)
  * type         = Group Type (Duo, Parent/Child, Trio/Quad, Team, Parent Team, Super Crew)
  * status       = Status (Active, Inactive)
  * main_dancers = An array with the Dancers in the Group
  *
*/
class DanceGroup {

  private $dance_school;
  private $id;

  private $group_name;

  private $size;

  private $type;

  private $status = 'Active';

  private $main_dancers;

  //$sub_dancers;
  //constructor
  function __construct($dance_school, $id, $group_name, $group_type) {
    $this->dance_school = $dance_school;
    $this->id = $id;
    $this->group_name = $group_name;
    $this->size = 0;
    $this->type = $group_type;
    $this->status = 'Active';
    $this->main_dancers = [];
  }

  //Get Dance School associated
  public function getDanceSchool() {
    return $this->dance_school;
  }

  //Get Group ID
  public function getID() {
    return $this->id;
  }

  //Get Group Name
  public function getGroupName() {
    return $this->group_name;
  }

  //Get Group Size
  public function getSize() {
    return sizeof($main_dancers);
  }

  //Get Group Type
  public function getType() {
    return $this->type;
  }

  //Get Group Status
  public function getStatus() {
    return $this->status;
  }

  //Set Group Status (Active / Inactive)
  public function setStatus($group_status) {
    $this->status = $group_status;
  }

  //Get Dancers in Group
  public function getDancers() {
    return $this->main_dancers;
  }

  //Add a Dancer in Group
  function addDancer($id) {
		if (!in_array($id, $this->main_dancers)) {
			array_push($this->main_dancers, $id);
      $this->size++;
      return true;
		}
    return false;
  }

  //Remove a Dancer from the Group
  function removeDancer($id) {
    if (!in_array($id, $this->main_dancers)) {
        return false;
    }
    $this->main_dancers = array_diff($this->main_dancers, [$id]);
    $this->size--;
    return true;
  }


}
?>
