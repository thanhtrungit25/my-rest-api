<?php

use Phalcon\Mvc\Model;

class Parts extends Model
{

  public $id;

  public $name;

  public function initialize()
  {
    $this->hasMany(
      "id",
      "RobotsParts",
      "parts_id",
      array(
        "foreignKey" => array(
          "message" => "The part cannot be deleted because other robots are using it"
        )
      )
    );
  }

}