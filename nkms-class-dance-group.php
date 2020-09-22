<?php

/**
  * DanceGroup Object to represent a dance group
  *
  * Attributes:
  * group_name      = Name
  * size            = Size (number of Dancers in Group)
  * type            = Group Type (Duo, Parent/Child, Trio/Quad, Team, Parent Team, Super Crew)
  * age category    = Age Category (6&u, 8&u, 10&u, 12&u, 14&u, 16&u, 17&o)
  * level category  = Level Category (Newcomer, Novice, Intermediate, Advanced)
  * status          = Status (Active, Inactive)
  * main_dancers    = An array with the Dancers (ID) in the Group
  *
*/
class DanceGroup {

  private $dance_school_id, $id, $group_name, $size, $type, $age_category, $level_category, $status, $main_dancers;

  // Constructor
  function __construct( $dance_school_id, $id, $group_name, $group_type, $level_category ) {
    $this->dance_school_id = $dance_school_id;
    $this->id = $id;
    $this->group_name = $group_name;
    $this->size = 0;
    $this->type = $group_type;
    $this->status = 'Active';
    $this->main_dancers = array();
    $this->age_category = '-';
    $this->level_category = $level_category;
  }

  // Get Dance School associated
  public function getDanceSchoolID() {
    return $this->dance_school_id;
  }

  // Get Group ID
  public function getID() {
    return $this->id;
  }

  // Get Group Name
  public function getGroupName() {
    return $this->group_name;
  }

  // Get Group Size
  public function getSize() {
    if ( ! empty( $this->getDancers() ) ) {
      return sizeof( $this->getDancers() );
    }
    else {
      return 0;
    }
  }

  // Get Group Type
  public function getType() {
    return $this->type;
  }

  // Get Group Status
  public function getStatus() {
    return $this->status;
  }

  // Set Group Status (Active / Inactive)
  public function setStatus( $group_status ) {
    $this->status = $group_status;
  }

  // Get Dancers in Group
  public function getDancers() {
    return $this->main_dancers;
  }

  // Get age category
  public function getAgeCategory() {
    return $this->age_category;
  }

  // Get level category
  public function getLevelCategory() {
    return $this->level_category;
  }

  // Change age category
  public function changeAgeCategory() {
    $dancer_ages = array();
    $dancers = $this->main_dancers;
    foreach ( $dancers as $dancer_id ) {
      $dancer = get_userdata( $dancer_id );
      $dob = $dancer->nkms_fields['dob'];
      $age = nkms_get_age( $dob );
      array_push( $dancer_ages, $age );
    }
    $aver_age = array_sum( $dancer_ages ) / count( $dancer_ages );
    if ( $aver_age <= 6 ) {
      $this->age_category = '6&u';
    }
    elseif ( $aver_age > 6 && $aver_age <= 8 ) {
      $this->age_category = '8&u';
    }
    elseif ( $aver_age > 8 && $aver_age <= 10 ) {
      $this->age_category = '10&u';
    }
    elseif ( $aver_age > 10 && $aver_age <= 12 ) {
      $this->age_category = '12&u';
    }
    elseif ( $aver_age > 12 && $aver_age <= 14 ) {
      $this->age_category = '14&u';
    }
    elseif ( $aver_age > 14 && $aver_age <= 16 ) {
      $this->age_category = '16&u';
    }
    else {
      $this->age_category = '17&o';
    }
    return $aver_age;
    // return $this->age_category;
  }

  // Change level category
  public function changeLevelCategory( $level_category ) {
    if ( $level_category == 'Newcomer' || $level_category == 'Novice' || $level_category == 'Intermediate' || $level_category == 'Advanced' ) {
      $this->level_category = $level_category;
      return true;
    }
    return false;
  }

  // Add a Dancer in Group
  function addDancer( $id ) {
		if ( ! in_array( $id, $this->main_dancers ) ) {
			array_push( $this->main_dancers, $id );
      $this->size++;
      return true;
		}
    return false;
  }

  // Remove a Dancer from the Group
  function removeDancer( $id ) {
    if ( ! in_array( $id, $this->main_dancers ) ) {
        return false;
    }
    $this->main_dancers = array_diff( $this->main_dancers, [$id] );
    $this->size--;
    return true;
  }
}
?>
