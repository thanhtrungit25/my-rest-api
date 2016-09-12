<?php

use Phalcon\Mvc\Model;

class RobotsParts extends Model
{

  public $id;

  public $robots_id;

  public $parts_id;

  public function initialize()
  {
    $this->belongsTo(
      "robots_id",
      "Robots",
      "id",
      array(
        "foreignKey" => true
      )
    );
    $this->belongsTo(
      "parts_id",
      "Parts",
      "id",
      array(
        "foreignKey" => array(
          "message" => "The part_id does not exist on the Parts model"
        )
      )
    );
  }

}