<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Robots extends Model
{
  public $id;

  public $name;

  public $year;

  public function initialize()
  {
    $this->hasMany("id", "RobotsParts", "robots_id");
  }

  /**
   * Return the related "robots parts"
   * @param  [type] $paremeters [description]
   * @return \RobotsParts[]
   */
  public function getRobotsParts($paremeters = null)
  {
    return $this->getRelated('RobotsParts', $paremeters);
  }

  public function validation()
  {
    // Type must be: droid, mechanical or virtual
    $this->validate(
      new InclusionIn(
        array(
          'field' => 'type',
          'domain' => array(
            'droid',
            'mechanical',
            'virtual'
          )
        )
      )
    );

    // Robot name must be unique
    $this->validate(
      new Uniqueness(
        array(
          'field' => 'name',
          'message' => 'The robot name must be unique'
        )
      )
    );

    // Year cannot be less than zero
    if ($this->year < 0) {
      $this->appendMessage(new Message('The year cannot be less than zero'));
    }

    // Check if any messages have been generated
    if ($this->validationHasFailed() == true) {
      return false;
    }

  }

}
